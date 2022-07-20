tinymce.init({
	selector: 'textarea.html_textarea',
	width: 620,
   /*  height: 300, */
    menubar: false,
    
    plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen paste image link media template codesample table charmap hr pagebreak nonbreaking anchor hr toc insertdatetime advlist lists wordcount imagetools textpattern responsivefilemanager code colorpicker textcolor importcss',
    toolbar1: "undo redo cut copy paste pastetext | formatselect styleselect",
	toolbar2: " bold italic underline strikethrough | forecolor forecolorpicker backcolor backcolorpicker | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ",
    toolbar3: "| link unlink anchor hr | image media file responsivefilemanager | emoticons | table | insertdatetime | code ",
   
    image_advtab: true,
    //paste_word_valid_elements: "b,strong,i,em,h1,h2",

    //absolute path for uploaded files
    relative_urls: false,
    remove_script_host: false,
    document_base_url: base_url,

    //css files
    content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        base_url+'css/style.css'
    ],

    //responsivefilemanager external plugin
	filemanager_crossdomain: true,
	filemanager_title: "Filemanager",
	external_filemanager_path: base_url+"tinymce/plugins/filemanager/",
    external_plugins: { "filemanager": base_url + "tinymce/plugins/filemanager/plugin.min.js", },
    
    file_picker_types: 'file image media',
    file_picker_callback: function (cb, value, meta) {
        var width = window.innerWidth - 30;
        var height = window.innerHeight - 60;
        if (width > 1800)width = 1800;
        if (height > 1200)height = 1200;
        if (width > 600) {
            var width_reduce = (width - 20) % 138;
            width = width - width_reduce + 10;
        }
        var urltype = 2;
        if (meta.filetype == 'image') {
            urltype = 1;
        }
        if (meta.filetype == 'media') {
            urltype = 3;
        }
        var title = "RESPONSIVE FileManager";
        if (typeof this.settings.filemanager_title !== "undefined" && this.settings.filemanager_title) {
            title = this.settings.filemanager_title;
        }
        var akey = "key";
        if (typeof this.settings.filemanager_access_key !== "undefined" && this.settings.filemanager_access_key) {
            akey = this.settings.filemanager_access_key;
        }
        var sort_by = "";
        if (typeof this.settings.filemanager_sort_by !== "undefined" && this.settings.filemanager_sort_by) {
            sort_by = "&sort_by=" + this.settings.filemanager_sort_by;
        }
        var descending = "false";
        if (typeof this.settings.filemanager_descending !== "undefined" && this.settings.filemanager_descending) {
            descending = this.settings.filemanager_descending;
        }
        var fldr = "";
        if (typeof this.settings.filemanager_subfolder !== "undefined" && this.settings.filemanager_subfolder) {
            fldr = "&fldr=" + this.settings.filemanager_subfolder;
        }
        var crossdomain = ""; 
        if (typeof this.settings.filemanager_crossdomain !== "undefined" && this.settings.filemanager_crossdomain) {
            //crossdomain = "&crossdomain=1";
            /*
            if (window.addEventListener) {
                window.addEventListener('message', filemanager_onMessage, false);
            } else {
                window.attachEvent('onmessage', filemanager_onMessage);
            }
            */
        }
        tinymce.activeEditor.windowManager.open({
            title: title,
            file: this.settings.external_filemanager_path + 'dialog.php?type=' + urltype + '&descending=' + descending + sort_by + fldr + crossdomain + '&lang=' + this.settings.language + '&akey=' + akey,
            width: width,
            height: height,
            resizable: true,
            maximizable: true,
            inline: 1
        }, {
            setUrl: function (url) {
                cb(url);
            }
        });
    }
});