// import * as markedjs from 'marked';
import {default as markedjs} from 'marked';

// import hljs from 'highlight.js';
// import "highlight.js/styles/tomorrow-night.css";
// init marked
markedjs.setOptions({
    renderer: new markedjs.Renderer(),
    highlight: function (code, language) {
        // console.log(code, language)
        // const hljs = require('highlight.js');
        // const validLanguage = hljs.getLanguage(language) ? language : 'plaintext';
        // console.log(hljs.highlight(validLanguage, code).value);
        // return hljs.highlight(validLanguage, code).value;
    },
    pedantic: false,
    gfm: true,
    breaks: true,
    sanitize: false,
    smartLists: true,
    smartypants: false,
    xhtml: true
})

export const marked = markedjs;