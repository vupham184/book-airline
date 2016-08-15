/**
 * AdminPraise
 *
 * @version		$Id
 * @package		Joomla
 * @subpackage	com_adminpraise
 * @copyright	Copyright 2006 - 2011 Matias Aguire. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 * @author		Matias Aguirre <maguirre@matware.com.ar>
 * @link		http://www.matware.com.ar
 */
window.addEvent('domready', function(){

	var dragDashBoard = new Class({
		dragSrcEl: '',

		initialize: function() {
			var self = this;
	
			$$('.module').each(function(el) {
				el.addEventListener('dragstart', function(e) {
					self.handleDragStart(e);
				}, false);

				el.addEventListener('dragenter', function(e) {
					self.handleDragEnter(e);
				}, false);
				el.addEventListener('dragover', function(e) {
					self.handleDragOver(e);
				}, false);
				el.addEventListener('dragleave', function(e) {
					self.handleDragLeave(e);
				}, false);
				el.addEventListener('drop', function(e) {
					self.handleDrop(e);
				}, false);
				el.addEventListener('dragend', function(e) {
					self.handleDragEnd(e);
				}, false);
			});   

		},

		handleDragStart: function(e) {
			//alert(e.target.id);

			this.dragSrcEl = e.target.id;

			e.dataTransfer.effectAllowed = 'move';
			e.dataTransfer.setData('text/html', e.target.get('html'));
			e.dataTransfer.setData('id', e.target.get('id'));
			document.id(this.dragSrcEl).addClass('moving');
		},

		handleDragEnter: function(e) {
		//e.target.addClass('over');
		},

		handleDragOver: function(e) {
			if (e.preventDefault) {
				e.preventDefault(); // Necessary. Allows us to drop.
			}

			//e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

			return false;
		},

		handleDragLeave: function(e) {
			document.id(this.dragSrcEl).removeClass('over');
		},

		handleDrop: function(e) {

			var dragSrcEl = this.dragSrcEl;

			// this/e.target is current target element.
			if (e.stopPropagation) {
				e.stopPropagation(); // Stops some browsers from redirecting.
			}

			// Don't do anything if dropping the same column we're dragging.
			if (dragSrcEl != e.target.id) {

				var dragToEl = e.target.getParent('div[draggable]').id;

				// Set the source column's HTML to the HTML of the columnwe dropped on.
				if (document.id(dragToEl).get('draggable')) {

					var sourceElementId = document.id(dragSrcEl).id;
					var dragToElementId = document.id(dragToEl).id;
					var sourceElementHtml = document.id(dragSrcEl).get('html');
					var dragToElementHtml = document.id(dragToEl).get('html');
					var sourceElementClass = document.id(dragSrcEl).get('class');
					var dragToElementClass = document.id(dragToEl).get('class');

					document.id(dragToEl).set('html', sourceElementHtml);
					document.id(dragSrcEl).set('html', dragToElementHtml);

					document.id(dragSrcEl).removeClass('moving');
					document.id(dragToEl).removeClass('over');

					document.id(dragToEl).set('id', sourceElementId);
					document.id(dragSrcEl).set('id', dragToElementId);

					
					// Update the database
					this.updateDatabase(sourceElementId, dragToElementId);
					// re-add events to modal windows
					this.addEventToModal();
					
				}

			}

		},

		handleDragEnd: function(e) {

		},
		
		addEventToModal: function() {
			SqueezeBox.initialize({});
			$$("a.modal").each(function(el) {
				el.addEvent("click", function(e) {
					new Event(e).stop();
					SqueezeBox.fromElement(el);
				});
			});
		},
		
		updateDatabase: function(from, to) {
			var request = new Request( {
				url: 'index.php?option=com_adminpraise&format=raw&task=ajax.changePosition',
				method: 'get',
				data: 'from='+from+'&to='+to,
				onComplete: function( response ) {

				}
			}).send();
		}

	});

	var myDrag = new dragDashBoard();

});
