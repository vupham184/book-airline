/**
 * Adminpraise
 *
 * @version			$Id: migrate.js 21936 2011-08-02 03:51:43Z maguirre $
 * @package			MatWare
 * @subpackage	com_jupgrade
 * @author      Matias Aguirre <maguirre@matware.com.ar>
 * @link        http://www.matware.com.ar
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */

var apInstaller = new Class({

    Implements: [Options, Events],

    options: {
        mode: 1
    },

    initialize: function(options) {
        var self = this;

        this.setOptions(options);

        $('compatibility').setStyle('display', 'none');
        $('component').setStyle('display', 'none');
        $('plugins').setStyle('display', 'none');
        $('modules').setStyle('display', 'none');
        $('template').setStyle('display', 'none');
        $('error').setStyle('display', 'none');
        $('done').setStyle('display', 'none');

        self.requirements();

    },

    /**
     * Run the requirements
     *
     * @return	bool
     * @since	1.0.0
     */
    requirements: function() {
        var self = this;

        var request = new Request({
            url: 'index.php?option=com_adminpraise&format=raw&controller=install&task=requirements',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
                var object = JSON.decode(response);
                var error = false;

                Object.each(object, function(value, key){
                    if (value != true) {
                        text = document.getElementById('requirements_text');
                        text.innetHTML = "<span class='red'>ERROR</span>";
                        var error = true;
                    }
                });

                if (error != true) {
                    $('requirements_text').set({html: '<span>DONE</span>', style: 'color: grey'})
                    self.compatibility();
                }
            }
        }).send();

    }, // end function

    /**
     * Run the compatibility
     *
     * @return	bool
     * @since	1.0.0
     */
    compatibility: function() {
        var self = this;
        var mySlideInstall = new Fx.Slide('compatibility');
        mySlideInstall.hide();
        $('compatibility').setStyle('display', 'block');
        mySlideInstall.toggle();

        var request = new Request.JSON({
            url: 'index.php?option=com_adminpraise&format=raw&controller=install&task=compatibility',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
                if(!response.status) {
                    $('compatibility_text').set({html: '<span>DONE</span>', style: 'color: grey'});
                    self.plugins();
                } else {
                    $('compatibility_text').set({html: 'Something went wrong. Please contact support. Aborting...' + response.message, style: 'color: grey'});
                    self.error();
                }
            }
        }).send();

    }, // end function

    plugins: function() {
        var self = this;

        var mySlideInstall = new Fx.Slide('plugins');
        mySlideInstall.hide();
        $('plugins').setStyle('display', 'block');
        mySlideInstall.toggle();

        var request = new Request({
            url: 'index.php?option=com_adminpraise&format=raw&controller=install&task=plugins',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
                //alert(response);
                $('plugins_text').set({html: '<span>DONE</span>', style: 'color: grey'})
                self.modules();

            }
        }).send();

    }, // end function

    modules: function() {
        var self = this;

        var mySlideInstall = new Fx.Slide('modules');
        mySlideInstall.hide();
        $('modules').setStyle('display', 'block');
        mySlideInstall.toggle();

        var request = new Request({
            url: 'index.php?option=com_adminpraise&format=raw&controller=install&task=modules',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
                //alert(response);
                $('modules_text').set({html: '<span>DONE</span>', style: 'color: grey'})
                self.template();
            }
        }).send();

    }, // end function

    template: function() {
        var self = this;

        var mySlideInstall = new Fx.Slide('template');
        mySlideInstall.hide();
        $('template').setStyle('display', 'block');
        mySlideInstall.toggle();

        var request = new Request({
            url: 'index.php?option=com_adminpraise&format=raw&controller=install&task=template',
            method: 'get',
            noCache: true,
            onComplete: function(response) {
                //alert(response);
                $('template_text').set({html: response, style: 'color: grey'})
                self.done();

            }
        }).send();

    }, // end function

    done: function() {
        var self = this;

        var mySlideInstall = new Fx.Slide('done');
        mySlideInstall.hide();
        $('done').setStyle('display', 'block');
        mySlideInstall.toggle();

        var request = new Request({
            url: 'index.php?option=com_adminpraise&format=raw&controller=install&task=done',
            method: 'get',
            noCache: true,
            onComplete: function(response) {

            }
        }).send();

    },

    error: function() {
        var self = this;

        var mySlideInstall = new Fx.Slide('error');
        mySlideInstall.hide();
        $('error').setStyle('display', 'block');
        mySlideInstall.toggle();
    }

});
