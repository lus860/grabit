$(function () {
    let url='';
    $('.make-all-notifications-as-read').on('click',function (e) {
        e.preventDefault();
        e.stopPropagation();

        // if (!$(this).hasClass('checked-us-read')){
        //     $('.check-admin-notify .fa-check').css('color','black');
        //     $('.make-all-notifications-as-read').addClass('checked-us-read');
        //     $('.send-ajax-for-read-notification').addClass('checked-for-admin');
        //     $('.make-all-notifications-as-read').html('check all unread');
        //     ajax_for_admin($('.checked-for-admin'));
        // }else{
        //     $('.check-admin-notify .fa-check').css('color','white');
        //     $('.make-all-notifications-as-read').removeClass('checked-us-read');
        //     $('.checked-for-admin').addClass('checked-for-admin-reverse');
        //     $('.checked-for-admin-reverse').removeClass('checked-for-admin');
        //     $('.make-all-notifications-as-read').html('check all read');
        //     ajax_for_admin($('.checked-for-admin-reverse'),true);
        // }
    });

    $('.check-admin-notify').on('click',function (e) {
        e.preventDefault();
        e.stopPropagation();
        url = set_url($(this).find('input').data('type'));
        let type_click = $(this).find('.send-ajax-for-read-notification');
        if(type_click.hasClass('checked-for-admin')){
            type_click.removeClass('checked-for-admin');
            type_click.addClass('checked-for-admin-reverse');
            $(this).find('i.fa-eye').removeClass('hide');
            $(this).addClass('btn-warning');
            $(this).removeClass('btn-success');
            $(this).find('i.fa-check').addClass('hide');
            let tr =  $(this).closest('tr.bg-info');
            if (tr.length){
                tr.addClass('bg-seen')
            }
            ajax_for_admin($('.checked-for-admin-reverse'),true)
        }else{
            $(this).find('i.fa-eye').addClass('hide');
            $(this).find('i.fa-check').removeClass('hide');
            $(this).removeClass('btn-warning');
            $(this).addClass('btn-success');
            let tr =  $(this).closest('tr.bg-info');
            if (tr.length){
                if (tr.hasClass('bg-seen')){
                    tr.removeClass('bg-seen');
                }
            }
            type_click.addClass('checked-for-admin');
            ajax_for_admin($('.checked-for-admin'));
        }
    });

    let ajax_for_admin =(that,type=false)=>{
        let notifications = $(that);
        let data=[];
            console.log(notifications);
        notifications.map((index,value)=>{
            $(value).data('id')?data[index]=$(value).data('id'):'';
        });
        console.log(data);
        $.ajax({
            url:`${url}`,
            type: "POST",
            data:{_token:$('input[name=_token]').val(),data:data,type:type ? true:false}
        }).done(function (answer) {
            if (answer.res == false){
                return false;
            }
        })
    },
        set_url = (type)=>{
            if (type === 'order'){return '/backend/order/admin-center-notification-change-status'}
            if (type === 'vendor'){return '/backend/vendor/admin-center-notification-change-status'}
        }
})