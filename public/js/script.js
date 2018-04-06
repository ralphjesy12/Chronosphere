jQuery(function(){
    $('.ui.dropdown').dropdown();

    window.confirmBox = function(title,question,positiveCallback,negativeCallback){
        // Remove All Confirm Boxes
        $('body .confirmBox').remove();
        if(!title){
            title = 'Confirm Action';
        }
        if(!question){
            question = 'Are you sure you want to do this?';
        }

        var template = '<div class="ui mini confirmBox modal"> <div class="header">'+ title +'</div> <div class="content"> <p>' + question + '</p> </div> <div class="actions"> <div class="ui negative button"> No </div> <div class="ui positive right labeled icon button"> Yes <i class="checkmark icon"></i> </div> </div> </div>';

        $('body').append(template);

        $('.confirmBox.modal').modal({
            closable: false,
            onApprove: typeof positiveCallback == 'function' ? positiveCallback : function(){},
            onDeny: typeof negativeCallback == 'function' ? negativeCallback : function(){},
        }).modal('show');

    }

    // alertBox(title,message);

    window.alertBox = function(title,message,positiveCallback){
        // Remove All Confirm Boxes
        $('body .alertBox').remove();
        if(!title){
            title = 'Alert Message';
        }
        if(!message){
            message = 'This page wants to tell you something.';
        }

        var template = '<div class="ui mini alertBox modal"> <div class="header">'+ title +'</div> <div class="content"> <p>' + message + '</p> </div> <div class="actions"> <div class="ui positive labeled icon button"><i class="checkmark icon"></i> OK  </div> </div> </div>';

        $('body').append(template);

        $('.alertBox.modal').modal({
            closable: false,
            onApprove: typeof positiveCallback == 'function' ? positiveCallback : function(){},
        }).modal('show');

    }

    $('body').on('click','.confirm-action',function(event){
        event.preventDefault();

        let _this = this;

        let title = 'Confirm Action';
        let question = 'Are you sure you want to do this?';

        let custom_question = $(_this).data('question');

        if(custom_question && custom_question.trim() != ''){
            question = custom_question.trim();
        }

        confirmBox(title,question,function(){
            $(_this)
            .first()
            .removeClass('confirm-action')
            .trigger('click')
            .addClass('confirm-action');

            if(href = $(_this).attr('href')){
                window.location.assign(href);
            }
        },function(){
            $(_this)
            .first()
            .removeClass('disabled loading');
        });

        return false;

    });

    // confirmBox('Delete Your Account','Are you sure you want to delete your account',function(){
    //     alert('Yes');
    // },function(){
    //     alert('No');
    // });


});
