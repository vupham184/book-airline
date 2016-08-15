/**
*    This file is part of Quick Item Pro.
*    
*    Quick Item Pro is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with Quick Item Pro.  If not, see <http://www.gnu.org/licenses/>.
*
**/
//function to add events on domready
window.addEvent('domready', function() {
	//click event for save button
	$('quickadd_save').addEvent('click', function(e) {
		var selected = $('itemtype').get('value');

		if(selected == 'content') {
			$('quickadd_task').setProperty('value', 'article.save');
		} else {
			$('quickadd_task').setProperty('value', 'save');
		}
		
		$('text').setProperty('value', getEditorContent('text'));			
		if (checkForm()){
			if(selected != 'content') {
				var formdata = $('quickAddContentForm').toQueryString();
					
			} else {
				var form = $('quickAddContentForm');
				var formdata = {
					jform: {
						title: document.id('title').get('value'),
						alias: document.id('alias').get('value'),
						catid: document.id('catid').get('value'),
						state: form.getElement('input[name=state]:checked').value,
						featured: form.getElement('input[name=featured]:checked').value,
						articletext: getEditorContent('text')
					},
					option: 'com_content',
					task: 'article.save'
				}
				formdata[quickItemProToken] = 1;
			}
			
			submitForm(formdata); 
		} else {			
			new Event(e).stop();
		}
	});
	//click event for apply button
//	$('quickadd_apply').addEvent('click', function(e) {
////alert('apply');
//		var selected = $('itemtype').get('value');
//
//		if(selected == 'content') {
//			$('quickadd_task').setProperty('value', 'article.apply');
//		} else {
//			$('quickadd_task').setProperty('value', 'apply');
//		}
//		
//		$('text').setProperty('value', getEditorContent('text'));			
//		if (checkForm()){
//			if(selected != 'content') {
//				var formdata = $('quickAddContentForm').toQueryString();
//					
//			} else {
//				var form = $('quickAddContentForm');
//				var formdata = {
//					jform: {
//						title: document.id('title').get('value'),
//						alias: document.id('alias').get('value'),
//						catid: document.id('catid').get('value'),
//						state: form.getElement('input[name=state]:checked').value,
//						featured: form.getElement('input[name=featured]:checked').value,
//						articletext: getEditorContent('text')
//					},
//					option: 'com_content',
//					task: 'article.apply'
//				}
//				formdata[quickItemProToken] = 1;
//			}
//			
//			submitForm(formdata);
//		} else {
//			new Event(e).stop();
//		}
//	});
	//click event for clear button
	$('quickadd_reset').addEvent('click', function(e) {
		clearForm();
	});
	//onchange event for dyna list
	$('itemtype').addEvent('change', function(e){
		var selected = $('itemtype').getProperty('value');
		changeDynaList('quickadd_catsList', 'catsList', selected);
		changeDynaList('quickadd_pubFeatList', 'pubFeatList', selected);
		if (selected=='flexicontent') { 
			$('quickadd_option').setProperty('value', 'com_'+selected);
			$('quickadd_controller').setProperty('value', 'items');
			$('quickadd_view').setProperty('value', 'item');
		} else if (selected=='k2') {
			$('quickadd_option').setProperty('value', 'com_'+selected);
			$('quickadd_controller').setProperty('value', 'item');
			$('quickadd_view').setProperty('value', 'item');
		} else {
			$('quickadd_option').setProperty('value', 'com_'+selected);
			$('quickadd_controller').setProperty('value', '');
			$('quickadd_view').setProperty('value', '');
		}
	});
});	
//function to change the categories list for sections
var getSecCatsList = function(){
	var selected =  $('sectionid').getProperty('value');
	changeDynaList('quickadd_secCatsList', 'secCatsList', selected);
}
//function to check form input 
var checkForm = function(){	
	if ($('quickadd_task').getProperty('value') != '') {
		var msg = '';
		if ($('title').getProperty('value')==''){ 
			msg += 'Please enter a title.\n'; }
		if ($('itemtype').getProperty('value') == '-1') { 
			msg += 'Please select a content type.\n'; }
		if (($('sectionid')!=null) && ($('sectionid').getProperty('value') == '-1')) {
			msg += 'Please select a section.\n'; } 
		if ((($('catid')!=null) && ($('catid').getProperty('value') == '-1')) ||
		(($('catid')!=null) && ($('catid').getProperty('value') == ''))) {
			msg += 'Please select a category.\n'; }		
		if (($('type_id')!=null) && ($('type_id').getProperty('value') == '0')) { 
			msg += 'Please select a FLEXIcontent Type.\n'; }
		if (isEmpty(getEditorContent('text'))) { 
			msg += 'Please enter some text.\n';}		
		if (msg != '') { 						
			alert(msg); 
			return false;
		} else { 
			return true;
		}
	}
}
//function to clear the form
var clearForm = function(){	
	$('quickadd_task').setProperty('value', 'apply');
	$('quickAddContentForm').getElements('[type=text]').setProperty('value')==''; 
	setEditorContent('text', '');
	$('itemtype').setProperty('value', '-1').fireEvent('change');
	var radios = $$('#quickAddContentForm input[type=radio]');
	for (var i=0; i<radios.length; i++) {
		var el = radios[i];
		var prev = i-1;
		if (i==0 || el.getProperty('name') != radios[prev].getProperty('name')) {
			el.setProperty('checked', true);
		} else {
			el.setProperty('checked', false);
		}
	}
}
//function to get the current editor
var getCurrentEditor = function() {
	if (this.JContentEditor) {
		editor = "jce";
	} else if (this.FCKeditorAPI) {
		editor = "fck";
	} else if (this.tinyMCE) {
		editor = "tiny";
	} else if (this.CKEDITOR) {
		editor = "ck";
	} else {
		editor = null;
	}
	return editor;
}
//function to get the editor content
var getEditorContent = function( name ) {
	var editor = getCurrentEditor();
	var content;
	switch( editor )
	{
	case "ck":
		content = this.CKEDITOR.instances[name].getData();
		break;
	case "fck":
		content = this.FCKeditorAPI.GetInstance(name).GetHTML();
		break;
	case "jce":
		content = this.JContentEditor.getContent(name);
		break;
	case "tiny":
		content = this.tinyMCE.activeEditor.getContent();
		break;
	default:
		content = $(name).value;
		break;
	}
	return content;
}
//function to set the editor content
var setEditorContent = function( name, content ) {
	var editor = getCurrentEditor();
	switch( editor )
	{
	case "ck":
		this.CKEDITOR.instances[name].setData(content);
		break;
	case "fck":
		this.FCKeditorAPI.GetInstance(name).SetHTML(content);
		break;
	case "jce":
		this.JContentEditor.setContent(name, content);
		break;
	case "tiny":
		this.tinyMCE.activeEditor.setContent(content);
		break;
	default:
		$(name).set('value', '');
		break;
	}
}
function isEmpty(str) {
    return (!str || 0 === str.length || /^\s*$/.test(str));
}