(function($) {
    $.Redactor.prototype.redactorUnderline = function() {
        return {
            langs: {
                en: {
                    'redactorUnderline': 'Custom Underline',
                },
            },
            init: function() {
				let button = this.button.addAfter('fullscreen', 'customUnderline', this.lang.get('redactorUnderline'));
				this.button.setIcon(button, '<i class="fa fa-underline"></i>');
				this.button.addCallback(button, this.redactorUnderline.toggle);
            },
            toggle: function() {
                let selectedHtml = $.parseHTML(this.selection.html());

                let sel = this.selection.get();
                let selectionText = this.selection.text();
                let selectionTextRange = this.selection.range();

                let style = {
                    'display': 'inline-block',
                    'position': 'relative',
                    'line-height': '1',
                };

                let svgStyleWrapper = {
                    'position': 'absolute',
                    'top': '95%',
                    'left': '0',
                    'width': '100%',
                    'height': '70%',
                }

                let svgStyle = {
                    'display': 'block',
                    'width': '100%',
                    'height': '100%',
                }

                if (selectedHtml instanceof jQuery && selectedHtml.hasClass('redactor-underline')) {
                    let text = document.createTextNode(selectedHtml.text());
                    selectionTextRange.deleteContents();
                    selectionTextRange.insertNode(text);
                } else if ($(sel.focusNode.parentNode).hasClass('redactor-underline')) {
                    let text = document.createTextNode(selectionText);
                    $(sel.focusNode.parentNode).remove();
                    selectionTextRange.deleteContents();
                    selectionTextRange.insertNode(text);
                } else {
                    let wrappedText = document.createElement('span')
                    wrappedText.classList.add('redactor-underline');

                    let svgContainer = document.createElement('span');
                    $(svgContainer).css(svgStyleWrapper);

                    let svg = $.parseHTML(`<svg width="87" height="17" viewBox="0 0 87 17" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                                               <path d="M1 6.26596L86 1L11.0511 16L77.8102 10.8936" stroke="currentColor" stroke-width="2"/>
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
