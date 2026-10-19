<?php

namespace App\Livewire\Cms;

use App\Livewire\Cms\Concerns\PesanValidasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PageaccountIndex extends Component
{
    use PesanValidasi;

    // form tambah / edit
    public $showForm = false;
    #[Locked]
    public $accountId;
    public $nama;
    public $email;
    public $password;
    public $role = 'admin';
    public $status = 'Y';

    // hanya super admin (dicek juga untuk setiap aksi Livewire)
    public function boot()
    {
        abort_unless(session('account_role') === 'super_admin', 403);
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $row = DB::table('accounts')->where('id', $id)->first();
        if (! $row) return;

        $this->resetForm();
        $this->accountId = $row->id;
        $this->nama = $row->nama;
        $this->email = $row->email;
        $this->role = $row->role;
        $this->status = $row->status;
        $this->showForm = true;
    }

    public function cancel()
    {
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset('showForm', 'accountId', 'nama', 'email', 'password', 'role', 'status');
        $this->resetValidation();
    }

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('accounts', 'email')->ignore($this->accountId)],
            'password' => $this->accountId ? 'nullable|string|min:8' : 'required|string|min:8',
            'role' => 'required|in:super_admin,admin',
            'status' => 'required|in:Y,N',
        ];
    }

    public function save()
    {
        $this->email = Str::lower(trim((string) $this->email));
        $this->validate();

        // akun sendiri tidak boleh diturunkan/nonaktif agar tidak terkunci
        if ($this->accountId == session('account_id') && ($this->role !== 'super_admin' || $this->status !== 'Y')) {
            $this->addError('role', 'Akun yang sedang dipakai harus tetap super admin dan aktif.');
            return;
        }

        $data = [
            'nama' => $this->nama,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'updated_at' => now(),
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->accountId) {
            DB::table('accounts')->where('id', $this->accountId)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('accounts')->insert($data);
        }

        $this->dispatch('toast', message: $this->accountId ? 'Akun berhasil diperbarui.' : 'Akun berhasil ditambahkan.');

        $this->resetForm();
    }

    public function delete($id)
    {
        if ($id == session('account_id')) {
            $this->dispatch('toast', message: 'Tidak bisa menghapus akun yang sedang dipakai.', type: 'error');
            return;
        }

        DB::table('accounts')->where('id', $id)->delete();

        $this->dispatch('toast', message: 'Akun berhasil dihapus.');
    }

    public function render()
    {
        // hash password tidak ikut dikirim ke browser
        return view('livewire.cms.pageaccount-index', [
            'data' => DB::table('accounts')
                ->select('id', 'nama', 'email', 'role', 'status', 'updated_at')
                ->orderBy('nama')
                ->get(),
        ]);
    }
}
