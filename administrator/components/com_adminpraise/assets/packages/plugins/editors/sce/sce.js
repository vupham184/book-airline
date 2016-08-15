/*****************************************/
// Name: Javascript Textarea BBCode Markup Editor
// Version: 1.3
// Author: Balakrishnan
// Last Modified Date: 25/jan/2009
// License: Free
// URL: http://www.corpocrat.com
/******************************************/

var textarea;
var content;
//document.write("<link href=\"bbeditor/styles.css\" rel=\"stylesheet\" type=\"text/css\">");


function edToolbar(obj, siteURL) {

	var imgUrl = siteURL+'plugins/editors/sce/images/';
	var output = '';
    output += "<div class=\"toolbar\">";
    output += "<img class=\"sce_button\" src=\""+imgUrl+"h1.gif\" name=\"btnH1\" title=\"H1\" onClick=\"doAddTags('<h1>','</h1>','" + obj + "')\">";
    output += "<img class=\"sce_button\" src=\""+imgUrl+"h2.gif\" name=\"btnH2\" title=\"H2\" onClick=\"doAddTags('<h2>','</h2>','" + obj + "')\">";
    output += "<img class=\"sce_button\" src=\""+imgUrl+"h3.gif\" name=\"btnH3\" title=\"H3\" onClick=\"doAddTags('<h3>','</h3>','" + obj + "')\">";
    output += "<img class=\"sce_button\" src=\""+imgUrl+"paragraph.gif\" name=\"btnParagraph\" title=\"Paragraph\" onClick=\"doAddTags('<p>','</p>','" + obj + "')\">";
    output += "<img class=\"sce_button\" src=\""+imgUrl+"break.gif\" name=\"btnBreak\" title=\"Line Break\" onClick=\"doBreak('" + obj + "')\">";
    output += "<img class=\"sce_button\" src=\""+imgUrl+"div.gif\" name=\"btnDiv\" title=\"Div\" onClick=\"doAddTags('<div>','</div>','" + obj + "')\">";
	output += "<img class=\"sce_button\" src=\""+imgUrl+"bold.gif\" name=\"btnBold\" title=\"Bold\" onClick=\"doAddTags('<strong>','</strong>','" + obj + "')\">";
    output += "<img class=\"sce_button\" src=\""+imgUrl+"italic.gif\" name=\"btnItalic\" title=\"Italic\" onClick=\"doAddTags('<em>','</em>','" + obj + "')\">";
	output += "<img class=\"sce_button\" src=\""+imgUrl+"underline.gif\" name=\"btnUnderline\" title=\"Underline\" onClick=\"doAddTags('<span style=\\'text-decoration:underline;\\'>','</span>','" + obj + "')\">";
	output += "<img class=\"sce_button\" src=\""+imgUrl+"link.gif\" name=\"btnLink\" title=\"Insert URL Link\" onClick=\"doURL('" + obj + "')\">";
	output += "<img class=\"sce_button\" src=\""+imgUrl+"picture.gif\" name=\"btnPicture\" title=\"Insert Image\" onClick=\"doImage('" + obj + "')\">";
	output += "<img class=\"sce_button\" src=\""+imgUrl+"ordered.gif\" name=\"btnList\" title=\"Ordered List\" onClick=\"doList('<ol>','</ol>','" + obj + "')\">";
	output += "<img class=\"sce_button\" src=\""+imgUrl+"unordered.gif\" name=\"btnList\" title=\"Unordered List\" onClick=\"doList('<ul>','</ul>','" + obj + "')\">";
	output += "<img class=\"sce_button\" src=\""+imgUrl+"quote.gif\" name=\"btnQuote\" title=\"Quote\" onClick=\"doAddTags('<blockquote>','</blockquote>','" + obj + "')\">"; 
  	output += "<img class=\"sce_button\" src=\""+imgUrl+"code.gif\" name=\"btnCode\" title=\"Code\" onClick=\"doAddTags('<pre>','</pre>','" + obj + "')\">";
  	output += "<img class=\"sce_button\" src=\""+imgUrl+"readmore.gif\" name=\"btnReadmore\" title=\"Read More\" onClick=\"doReadmore('" + obj + "')\">";
    output += "</div>";
	//document.write("<textarea id=\""+ obj +"\" name = \"" + obj + "\" cols=\"" + width + "\" rows=\"" + height + "\"></textarea>");
	
	var toolbarId = obj+'_toolbar';
	document.getElementById(toolbarId).innerHTML = output;
	
				}

function doReadmore(obj)
{
	textarea = document.getElementById(obj);
	var scrollTop = textarea.scrollTop;
	var scrollLeft = textarea.scrollLeft;



	var len = textarea.value.length;
	var start = textarea.selectionStart;
	var end = textarea.selectionEnd;
	
	var sel = textarea.value.substring(start, end);
	//alert(sel);
	var rep = '<hr id="system-readmore" />';
	textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
	
	    
	textarea.scrollTop = scrollTop;
	textarea.scrollLeft = scrollLeft;
	

}

function doBreak(obj)
{
	textarea = document.getElementById(obj);
	var scrollTop = textarea.scrollTop;
	var scrollLeft = textarea.scrollLeft;



	var len = textarea.value.length;
	var start = textarea.selectionStart;
	var end = textarea.selectionEnd;
	
	var sel = textarea.value.substring(start, end);
	//alert(sel);
	var rep = '<br />';
	textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
	
	    
	textarea.scrollTop = scrollTop;
	textarea.scrollLeft = scrollLeft;
	

}

function doImage(obj)
{
textarea = document.getElementById(obj);
var url = prompt('Enter the Image URL:','http://');
var scrollTop = textarea.scrollTop;
var scrollLeft = textarea.scrollLeft;

if (url != '' && url != null) {

	if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				sel.text = '<img src="' + url + '" />';
			}
   else 
    {
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		var rep = '<img src="' + url + '" />';
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
			
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
	}
}

}

function doURL(obj)
{
textarea = document.getElementById(obj);
var url = prompt('Enter the URL:','http://');
var scrollTop = textarea.scrollTop;
var scrollLeft = textarea.scrollLeft;

if (url != '' && url != null) {

	if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				
			if(sel.text==""){
					sel.text = '<a>'  + url + '</a>';
					} else {
					sel.text = '<a href="' + url + '">' + sel.text + '</a>';
					}			

				//alert(sel.text);
				
			}
   else 
    {
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
        var sel = textarea.value.substring(start, end);
		
		if(sel==""){
				var rep = '<a>'  + url + '</a>';
				} else
				{
				var rep = '<a href="' + url + '">' + sel + '</a>';
				}
	    //alert(sel);
		
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
			
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
	}
 }
}

function doAddTags(tag1,tag2,obj)
{
textarea = document.getElementById(obj);
	// Code for IE
		if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				//alert(sel.text);
				sel.text = tag1 + sel.text + tag2;
			}
   else 
    {  // Code for Mozilla Firefox
		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		
		
		var scrollTop = textarea.scrollTop;
		var scrollLeft = textarea.scrollLeft;

		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		var rep = tag1 + sel + tag2;
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
		
		
	}
}

function doList(tag1,tag2,obj){
textarea = document.getElementById(obj);
// Code for IE
		if (document.selection) 
			{
				textarea.focus();
				var sel = document.selection.createRange();
				var list = sel.text.split('\n');
		
				for(i=0;i<list.length;i++) 
				{
				list[i] = '<li>' + list[i] + '</li>';
				}
				//alert(list.join("\n"));
				sel.text = tag1 + '\n' + list.join("\n") + '\n' + tag2;
			} else
			// Code for Firefox
			{

		var len = textarea.value.length;
	    var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var i;
		
		var scrollTop = textarea.scrollTop;
		var scrollLeft = textarea.scrollLeft;

		
        var sel = textarea.value.substring(start, end);
	    //alert(sel);
		
		var list = sel.split('\n');
		
		for(i=0;i<list.length;i++) 
		{
		list[i] = '<li>' + list[i] + '</li>';
		}
		//alert(list.join("<br>"));
        
		
		var rep = tag1 + '\n' + list.join("\n") + '\n' +tag2;
		textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
		
		textarea.scrollTop = scrollTop;
		textarea.scrollLeft = scrollLeft;
 }
}
