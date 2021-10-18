const initNotification = function () {
    const sendComplaint = function (e) {
        const button = e.target.closest(".complaint");
        if (!button || $(button).hasClass('disabled')) {
            return;
        }
        e.preventDefault();
        $.post({
            url: '/api/v1/post/' + $(e.target).parents(".buttons").data().postId + '/complaint',
        }).done(function () {
            $(button).addClass('disabled');
        })
    }
    document.addEventListener("click", sendComplaint);

    const sendRating = function (e) {
        const button = e.target.closest(".rating");
        if (!button || $(button).hasClass('disabled')) {
            return;
        }
        e.preventDefault();
        const buttons = $(e.target).parents(".buttons").children('.d-flex').children('.rating');
        const id = $(e.target).parents(".buttons").data().postId;
        $.post({
            url: '/api/v1/post/' + id + '/rating',
            data: {'rating': $(button).data().rating}
        }).done(function () {
            buttons.addClass('disabled');
            $.get({
                url: '/api/v1/rating/' + id
            }).done(function (data) {
                $('#sum_rating_' + id).html(data.rating)
            })
        })
    }
    document.addEventListener("click", sendRating);

    const setPostLink = function (e) {
        const row = e.target.closest(".post_row");
        if (!row) {
            return;
        }
        e.preventDefault();
        location.href = '/' + $(row).data().postId;
    }
    document.addEventListener("click", setPostLink);
}
initNotification();