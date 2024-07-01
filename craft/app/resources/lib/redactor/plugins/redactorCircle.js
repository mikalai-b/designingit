(function($) {
    $.Redactor.prototype.redactorCircle = function() {
        return {
            langs: {
                en: {
                    'redactorCircle': 'Circle over words',
                },
            },
            init: function() {
				let button = this.button.addAfter('fullscreen', 'circle', this.lang.get('redactorCircle'));
				this.button.setIcon(button, '<i class="fa fa-circle-thin"></i>');
				this.button.addCallback(button, this.redactorCircle.toggle);
            },
            toggle: function() {
                let selectedHtml = $(this.selection.html());

                let sel = this.selection.get();
                let selectionText = this.selection.text();
                let selectionTextRange = this.selection.range();

                let style = {
                    'display': 'inline-block',
                    'position': 'relative',
                    'padding': '2px',
                };

                let svgStyleWrapper = {
                    "position": "absolute",
                    "inset": "0",
                }

                let svgStyle = {
                    'display': 'block',
                    'position': 'absolute',
                    'width': '115%',
                    'height': '115%',
                    'left': '-10%',
                    'top': '-10%',
                }

                if (selectedHtml instanceof jQuery && selectedHtml.hasClass('redactor-circle')) {
                    let text = document.createTextNode(selectedHtml.text());
                    selectionTextRange.deleteContents();
                    selectionTextRange.insertNode(text);
                } else if ($(sel.focusNode.parentNode).hasClass('redactor-circle')) {
                    let text = document.createTextNode(selectionText);
                    $(sel.focusNode.parentNode).remove();
                    selectionTextRange.deleteContents();
                    selectionTextRange.insertNode(text);
                } else {
                    let wrappedText = document.createElement('span')
                    wrappedText.classList.add('redactor-circle');

                    let svgContainer = document.createElement('span');
                    $(svgContainer).css(svgStyleWrapper);

                    let svg = $.parseHTML(`<svg width="78" height="36" viewBox="0 0 78 36" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                                                  <path d="M51.6125 3.64986C39.85 0.105077 -2.98385 8.88081 7.3672 27.8885C13.0676 36.1277 35.2355 37.0858 50.8888 27.8885C66.542 18.6912 78.9379 8.72752 59.1226 3.64986C39.3072 -1.4278 24.1939 1.27025 12.8838 7.68917C1.57364 14.1081 -1.32182 20.7989 2.75049 26.3556C6.88999 32.004 20.5753 36.7025 37.4048 34.4032C54.2343 32.1039 65.8153 28.2872 75.2259 20.1283C79.1768 16.7029 77.9403 9.22206 56.6795 8.91912" stroke="currentColor"/>
                                               </svg>`);
                    $(svg).css(svgStyle);

                    $(svgContainer).append(svg);

                    $(wrappedText).append(svgContainer);
                    $(wrappedText).append(selectionText);
                    $(wrappedText).css(style);

                    selectionTextRange.deleteContents();
                    selectionTextRange.insertNode(wrappedText);
                }
                this.selection.update(sel, selectionTextRange);
            },
        }
    }
})(jQuery);
