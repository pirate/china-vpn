//Variable Declaration
var no_of_answers;
var show_alert;
var i;
var answer_type;
var max_no_answers;
var ajaxurl;

//Body on load
jQuery(document).ready(function(){
    
    ajaxurl = CwppPlgSettings.ajaxurl+ "?nonce=" + CwppPlgSettings.nonce;
        
    no_of_answers = 2;   
    jQuery('#start_date').datepicker();
    jQuery('#end_date').datepicker();
    jQuery('#image_start_date').datepicker();
    jQuery('#image_end_date').datepicker();
    jQuery("#no-of-answers, #widget-height, #widget-width, #poll-bar-height").keydown(function(event) {
        // Allow: backspace, delete, tab and escape
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });

});

function appendAnswers(){
    no_of_answers = no_of_answers + 1;
    jQuery('.answers').append('<tr id="answer' + no_of_answers + '"><td>Answer ' + no_of_answers + '* :</td><td><input id="ans'+ no_of_answers+'" style="width:350px;" name="answer' + no_of_answers + '"  type="text" value="" /></td></tr>');
}

function showanswers(value){
    
    if(value=="one") jQuery('#nanswers').css('display', 'none');
    else jQuery('#nanswers').css('display', 'block');
}

function removeAnswers(){    
    if(no_of_answers <=2) jAlert("You should have atleast 2 answers for a poll!", 'Alert!');
    else{
        jQuery('#answer'+no_of_answers).remove();
        no_of_answers = no_of_answers-1;   
    }
}

function validateAddNewPollForm(){
    
    show_alert = 0;
    
    if((jQuery('#poll-name').val()=="") ||  (jQuery('#poll-question').val()=="")){
        show_alert = 1;
    }
    
    if(jQuery('#poll-answer-type').val()=="multiple"){
        
        if((jQuery('#no-of-answers').val()>no_of_answers)||(jQuery('#no-of-answers').val()<2)){
            
            jAlert("The number of answers found is less than the value you specified", 'Warning!');
            show_alert = 1;
        }
    }
    
    for(i=1; i<=no_of_answers; i++){
        if(jQuery('#ans'+no_of_answers).val()==""){
            show_alert = 1;
        }
    }
    
    if(show_alert==1) jAlert("All the mandatory (*) fields should be filled in to add new poll!", 'Alert!');
    else{
        jConfirm('Do you want to create this poll?', 'Confirmation Dialog', function(r) {
            if(r==true){
                jAlert("Poll saved successfully!", "Save message");
                jQuery.post(ajaxurl, jQuery('#create-poll').serialize(),  
                    function(response){
                        clearAll();
                    }
                );
            }
        });
    }
    
}

function validateAddNewImagePollForm(){
    
    show_alert = 0;
    
    if((jQuery('#image-poll-name').val()=="") ||  (jQuery('#image-poll-question').val()=="")){
        show_alert = 1;
    }
    
    if(jQuery('#image-poll-answer-type').val()=="multiple"){
        
        if((jQuery('#image-no-of-answers').val()>no_of_answers)||(jQuery('#image-no-of-answers').val()<2)){
            
            jAlert("The number of answers found is less than the value you specified", 'Warning!');
            show_alert = 1;
        }
    }
    
    for(i=1; i<=no_of_answers; i++){
        if(jQuery('#image-ans'+no_of_answers).val()==""){
            show_alert = 1;
        }
    }
    
    if(show_alert==1) jAlert("All the mandatory (*) fields should be filled in to add new poll!", 'Alert!');
    else{
        jConfirm('Do you want to create this poll?', 'Confirmation Dialog', function(r) {
            if(r==true){
                jAlert("Poll saved successfully!", "Save message");
                jQuery.post(ajaxurl, jQuery('#image-create-poll').serialize(),  
                    function(response){
                        clearAll();
                    }
                );
            }
        });
    }
    
}

function vote_poll(pollid, answertype, maxnoanswers){
    
    if(answertype == 'multiple')
    {
        data = jQuery('#poll'+pollid).serialize();
        var n=data.match(/option/g);
        if(parseInt(n.length) <= parseInt(maxnoanswers))
            {
                jQuery('#show-form'+pollid).fadeOut(500);
                jQuery('#show-results'+pollid).css('display', 'none');
                jQuery.post(ajaxurl, data,  
                    function(response){
                        jQuery('#poll'+pollid).html(response);
                        jQuery('#pollsc'+pollid).html(response);
                    }
                );
            }
        else jAlert("Sorry! Maximum no of answers allowed is " + maxnoanswers, "Error message");
    }
    if(answertype == 'one')
    {
        data = jQuery('#poll'+pollid).serialize();
        jQuery('#show-form'+pollid).fadeOut(500);
        jQuery('#show-results'+pollid).css('display', 'none');
        jQuery.post(ajaxurl, data,  
            function(response){
                jQuery('#poll'+pollid).html(response);
                jQuery('#pollsc'+pollid).html(response);
            }
        ); 
    }
}


function vote_poll_sc(pollid, answertype, maxnoanswers){
  
    if(answertype == 'multiple')
    {
        data = jQuery('#pollsc'+pollid).serialize();
        var n=data.match(/option/g);
        if(parseInt(n.length) <= parseInt(maxnoanswers))
            {
                jQuery('#show-form'+pollid).fadeOut(500);
                jQuery('#show-results'+pollid).css('display', 'none');
                jQuery.post(ajaxurl, data,  
                    function(response){
                        jQuery('#poll'+pollid).html(response);
                        jQuery('#pollsc'+pollid).html(response);
                    }
                );
            }
        else jAlert("Sorry! Maximum no of answers allowed is " + maxnoanswers, "Error message");
    }
    if(answertype == 'one')
    {
        data = jQuery('#pollsc'+pollid).serialize();
        jQuery('#show-form'+pollid).fadeOut(500);
        jQuery('#show-results'+pollid).css('display', 'none');
        jQuery.post(ajaxurl, data,  
            function(response){
                jQuery('#poll'+pollid).html(response);
                jQuery('#pollsc'+pollid).html(response);
            }
        ); 
    }    
}

function cancel_vote_poll(pollid){
    jQuery('#show-form'+pollid).fadeIn(500);
    jQuery('#show-results'+pollid).fadeOut(500);
    jQuery.post(ajaxurl, jQuery('#poll'+pollid).serialize(),  
        function(response){
            jQuery('#poll'+pollid).html(response);
        }
    );
}

function refreshPollList(){
    var data = {
        action: 'refresh_poll_list'
    };
    jQuery.post(ajaxurl, data,  
        function(response){
            jQuery('#all-polls').html(response);
        }
    );
}

function editAnswer(answerid, poll_id){
    jPrompt('Enter the answer :', '', 'Prompt Dialog', function(inp) {
        if(inp) {
            jConfirm('Do you want to save this answer?', 'Confirmation Dialog', function(r) {
                if(r==true){
                    jQuery('#refresh-poll-list').fadeOut(1000);
                    var data = {
                        action: 'update_answer',
                        answer: inp,
                        answer_id: answerid,
                        pollid: poll_id
                    };
                    jQuery.post(ajaxurl, data,  
                        function(response){
                            if(response!=0){
                                jQuery('#all-polls').html(response);
                            }
                            else{
                                jAlert("Sorry the poll id you entered not found!", "Error message");
                            }
                        }
                    );
                }
            });
        }
    });
}

function deleteAnswer(answerid, poll_id){
    
    jConfirm('Do you want to delete this answer?', 'Confirmation Dialog', function(r) {
        if(r==true){
            
            var data = {
                action: 'delete_answer',
                answer_id: answerid,
                pollid: poll_id
            };
            jQuery.post(ajaxurl, data,  
                function(response){
                    if(response!=0){
                        jQuery('#all-polls').html(response);
                    }
                    else{
                        jAlert("Sorry the poll id you entered not found!", "Error message");
                    }
                }
            );
        }
    });
    
}

function addAnswer(polls_id){
    
    jPrompt('Enter the answer :', '', 'Prompt Dialog', function(inp) {
        if(inp) {
            jConfirm('Do you want to save this answer?', 'Confirmation Dialog', function(r) {
                if(r==true){
                    jQuery('#refresh-poll-list').fadeOut(1000);
                    var data = {
                        action: 'add_answer',
                        answer: inp,
                        pollid: polls_id
                    };
                    jQuery.post(ajaxurl, data,  
                        function(response){
                            if(response!=0){
                                jQuery('#all-polls').html(response);
                            }
                            else{
                                jAlert("Sorry the poll id you entered not found!", "Error message");
                            }
                        }
                    );
                }
            });
        }
    });
}

function save_changes(){
    jConfirm('Do you want to save these poll changes?', 'Confirmation Dialog', function(r) {
        if(r==true){
            jQuery('#manage-polls').fadeIn(500);
            jAlert("Poll saved successfully!", "Save message");
            jQuery.post(ajaxurl, jQuery('#edit-poll-form').serialize(),  
                function(response){
                    jQuery('#message').html(response);
                }
            );
        }
    });
}

function deletePoll(){
    jPrompt('Enter the poll id you want to delete:', '', 'Prompt Dialog', function(inp) {
        if(inp) {
            jConfirm('Do you want to delete this poll?', 'Confirmation Dialog', function(r) {
                if(r==true){
                    jQuery('#refresh-poll-list').fadeOut(1000);
                    var data = {
                        action: 'deletepoll',
                        pollid: inp
                    };
                    jQuery.post(ajaxurl, data,  
                        function(response){
                            if(response!=0){
                                jQuery('#all-polls').html(response);
                            }
                            else{
                                jAlert("Sorry the poll id you entered not found!", "Error message");
                            }
                        }
                    );
                }
            });
        }
    });
}

function editPoll(){
    jPrompt('Enter the poll id you want to edit:', '', 'Prompt Dialog', function(inp) {
        if(inp) {
            jConfirm('Do you want to edit this poll?', 'Confirmation Dialog', function(r) {
                if(r==true){
                    jQuery('#refresh-poll-list').fadeOut(1000);
                    var data = {
                        action: 'editpoll',
                        pollid: inp
                    };
                    jQuery.post(ajaxurl, data,  
                        function(response){
                            if(response!=0){
                                jQuery('#manage-polls').html(response);
                                jQuery('#manage-polls').show({
                                    effect : 'slide',
                                    easing : 'easeOutQuart',
                                    direction : 'up',
                                    duration : 1000
                                });
                            }
                            else{
                                jAlert("Sorry the poll id you entered not found!", "Error message");
                            }
                        }
                    );
                }
            });
        }
    });
}

function getPollStatsjs(arg){
    var data = {
            action: 'view_poll_stats',
            arguments: arg
        };
    jQuery.post(ajaxurl, data,  
        function(response){
            jQuery('#cwp-graph').html(response);
        }
    );
}

function userlogs(pollid){
    var data = {
            action: 'view_poll_logs',
            pollid: pollid
        };
    jQuery.post(ajaxurl, data,  
        function(response){
            jQuery('#poll-logs').html(response);
            jQuery('#poll-logs').show({
                effect : 'slide',
                easing : 'easeOutQuart',
                direction : 'up',
                duration : 1000
            });
        }
    );
}

function viewPollResults(pollid){
    
    var data = {
            action: 'view_poll_result',
            poll_id: pollid
        };
    jQuery.post(ajaxurl, data,  
        function(response){
            jAlert(response, "Result for pollid : "+pollid);
        }
    );
}

function clearAll(){
    jQuery('#poll-name').val('');
    jQuery('#poll-question').val('');
    for(i=1; i<=no_of_answers; i++){
        jQuery('#ans'+i).val('');
    }
}

function showresults(id){
    jQuery('.show-form'+id).css('display', 'none');
    jQuery('.showresultslink'+id).css('display', 'none');
    jQuery('.show-results'+id).css('display', 'block');
}

function showforms(id){
    jQuery('.show-results'+id).css('display', 'none');
    jQuery('.showresultslink'+id).css('display', 'block');
    jQuery('.show-form'+id).css('display', 'block');
}

(function($) {
	
	$.alerts = {
		
		// These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time
		
		verticalOffset: -75,                // vertical offset of the dialog from center screen, in pixels
		horizontalOffset: 0,                // horizontal offset of the dialog from center screen, in pixels/
		repositionOnResize: true,           // re-centers the dialog on window resize
		overlayOpacity: .01,                // transparency level of overlay
		overlayColor: '#FFF',               // base color of overlay
		draggable: true,                    // make the dialogs draggable (requires UI Draggables plugin)
		okButton: '&nbsp;Continue&nbsp;',         // text for the OK button
		cancelButton: '&nbsp;Cancel&nbsp;', // text for the Cancel button
		dialogClass: null,                  // if specified, this class will be applied to all dialogs
		
		// Public methods
		
		alert: function(message, title, callback) {
			if( title == null ) title = 'Alert';
			$.alerts._show(title, message, null, 'alert', function(result) {
				if( callback ) callback(result);
			});
		},
		
		confirm: function(message, title, callback) {
			if( title == null ) title = 'Confirm';
			$.alerts._show(title, message, null, 'confirm', function(result) {
				if( callback ) callback(result);
			});
		},
			
		prompt: function(message, value, title, callback) {
			if( title == null ) title = 'Prompt';
			$.alerts._show(title, message, value, 'prompt', function(result) {
				if( callback ) callback(result);
			});
		},
		
		// Private methods
		
		_show: function(title, msg, value, type, callback) {
			
			$.alerts._hide();
			$.alerts._overlay('show');
			
			$("BODY").append(
			  '<div id="popup_container">' +
			    '<h1 id="popup_title"></h1>' +
			    '<div id="popup_content">' +
			      '<div id="popup_message"></div>' +
				'</div>' +
			  '</div>');
			
			if( $.alerts.dialogClass ) $("#popup_container").addClass($.alerts.dialogClass);
			
			// IE6 Fix
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
			
			$("#popup_container").css({
				position: pos,
				zIndex: 99999,
				padding: 0,
				margin: 0
			});
			
			$("#popup_title").text(title);
			$("#popup_content").addClass(type);
			$("#popup_message").text(msg);
			$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );
			
			$("#popup_container").css({
				minWidth: $("#popup_container").outerWidth(),
				maxWidth: $("#popup_container").outerWidth()
			});
			
			$.alerts._reposition();
			$.alerts._maintainPosition(true);
			
			switch( type ) {
				case 'alert':
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /></div>');
					$("#popup_ok").click( function() {
						$.alerts._hide();
						callback(true);
					});
					$("#popup_ok").focus().keypress( function(e) {
						if( e.keyCode == 13 || e.keyCode == 27 ) $("#popup_ok").trigger('click');
					});
				break;
				case 'confirm':
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_ok").click( function() {
						$.alerts._hide();
						if( callback ) callback(true);
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback(false);
					});
					$("#popup_ok").focus();
					$("#popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
				break;
				case 'prompt':
					$("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />').after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_prompt").width( $("#popup_message").width() );
					$("#popup_ok").click( function() {
						var val = $("#popup_prompt").val();
						$.alerts._hide();
						if( callback ) callback( val );
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback( null );
					});
					$("#popup_prompt, #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_prompt").val(value);
					$("#popup_prompt").focus().select();
				break;
			}
			
			// Make draggable
			if( $.alerts.draggable ) {
				try {
					$("#popup_container").draggable({ handle: $("#popup_title") });
					$("#popup_title").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
		},
		
		_hide: function() {
			$("#popup_container").remove();
			$.alerts._overlay('hide');
			$.alerts._maintainPosition(false);
		},
		
		_overlay: function(status) {
			switch( status ) {
				case 'show':
					$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay"></div>');
					$("#popup_overlay").css({
						position: 'absolute',
						zIndex: 99998,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay").remove();
				break;
			}
		},
		
		_reposition: function() {
			var top = (($(window).height() / 2) - ($("#popup_container").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			
			$("#popup_container").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay").height( $(document).height() );
		},
		
		_maintainPosition: function(status) {
			if( $.alerts.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', $.alerts._reposition);
					break;
					case false:
						$(window).unbind('resize', $.alerts._reposition);
					break;
				}
			}
		}
		
	}
	
	// Shortuct functions
	jAlert = function(message, title, callback) {
		$.alerts.alert(message, title, callback);
	}
	
	jConfirm = function(message, title, callback) {
		$.alerts.confirm(message, title, callback);
	};
		
	jPrompt = function(message, value, title, callback) {
		$.alerts.prompt(message, value, title, callback);
	};
	
})(jQuery);