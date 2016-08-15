// Sticky top nav

window.addEvent('domready', function () {
    var alt = document.id('alt-toolbar');
    window.addEvent('scroll', function () {
        if (document.documentElement.scrollTop > 132 || self.pageYOffset > 132) {
            if (alt) {
                alt.set({
                    styles:{
                        position:'fixed',
                        top:'0',
                        width:'95%'
                    },
                    'class':'sticky-tools'
                });
            }
        } else if (document.documentElement.scrollTop < 132 || self.pageYOffset < 132) {
            if (alt) {
                alt.set({
                    styles:{
                        position:'static',
                        top:'0'
                    },
                    'class':''
                });
            }
        }
    });

});