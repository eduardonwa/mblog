<div 
    x-data="{
        show: false,
        iframeHtml: '',
        statePath: '',
        open(path) {
            this.show = true;
            this.statePath = path;
            this.iframeHtml = '';
        },
        close() {
            this.show = false;
            this.iframeHtml = '';
            this.statePath = '';
        },
        insert() {
            const doc = new DOMParser().parseFromString(this.iframeHtml, 'text/html');
            const iframe = doc.querySelector('iframe');
            let src = this.iframeHtml.trim();
            if (iframe) src = iframe.getAttribute('src') || '';
            if (!src) { alert('No se detectó un src válido.'); return; }
            window.$tiptapEditors[this.statePath]?.commands.setBandcampIframe({
                src: src,
                width: '100%',
                height: '120px'
            });
            this.close();
        }
    }"
    x-init="
        window.addEventListener('open-bandcamp-modal', e => open(e.detail.statePath));
    "
>
    <h2>hola Dios</h2>
</div>
