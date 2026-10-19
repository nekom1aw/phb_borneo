{{-- Editor TinyMCE (self-hosted di public/tinymce) yang tersambung ke properti Livewire. Id editor: "editor-<model>" --}}
@props(['model', 'height' => 380, 'placeholder' => 'Tulis isi di sini…'])

@php $editorId = 'editor-' . $model; @endphp

<div wire:ignore class="cms-editor"
     x-data="{
        editor: null,
        init() {
            if (window.tinymce && tinymce.get('{{ $editorId }}')) tinymce.get('{{ $editorId }}').remove();

            tinymce.init({
                target: this.$refs.editor,
                license_key: 'gpl',
                language: 'id',
                language_url: '{{ asset('tinymce/langs/id.js') }}',
                height: {{ (int) $height }},
                min_height: 260,
                menubar: false,
                statusbar: false,
                placeholder: @js($placeholder),
                plugins: 'autolink autoresize link lists image media table',
                toolbar: 'blocks | bold italic | bullist numlist | link image media | alignleft aligncenter | undo redo',
                toolbar_mode: 'wrap',
                block_formats: 'Paragraf=p; Subjudul=h2; Subjudul kecil=h3',
                autoresize_bottom_margin: 24,
                max_height: 720,
                promotion: false,
                branding: false,
                relative_urls: false,
                remove_script_host: true,
                image_caption: true,
                image_dimensions: false,
                image_description: true,
                link_default_target: '_blank',
                link_title: false,
                paste_data_images: true,
                content_style: 'body { font-family: Poppins, sans-serif; font-size: 16px; line-height: 1.7; color: #1c1917; margin: 16px 18px; } img { max-width: 100%; height: auto; border-radius: 8px; } h2, h3 { line-height: 1.3; }',
                images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('file', blobInfo.blob(), blobInfo.filename());
                    fetch('{{ route('cms.tinymce.upload') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: data,
                    })
                        .then((res) => res.ok ? res.json() : Promise.reject('Foto gagal diunggah (maks 5 MB, format JPG/PNG/WebP).'))
                        .then((json) => resolve(json.location))
                        .catch((err) => reject({ message: String(err), remove: true }));
                }),
                setup: (editor) => {
                    this.editor = editor;
                    editor.on('init', () => editor.setContent($wire.get('{{ $model }}') || ''));
                    editor.on('change input undo redo', () => {
                        $wire.$set('{{ $model }}', editor.getContent(), false);
                        window.dispatchEvent(new CustomEvent('cms-dirty'));
                    });
                },
            });
        },
        destroy() {
            this.editor && this.editor.remove();
        },
     }">
    <textarea x-ref="editor" id="{{ $editorId }}"></textarea>
</div>
