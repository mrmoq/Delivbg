$(document).ready(function() {
    $("#image").change(function() {
        showImagePreview(this);
    });

    $(".img-control").change(function() {
        showImagePreviewByClass(this);
    });

    // to show image preview before uploading
    function showImagePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = $('#image_preview');
                imagePreview.attr('src', e.target.result);
                imagePreview.attr('hidden', false);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // to show image preview before uploading
    function showImagePreviewByClass(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = $('.img_preview');
                imagePreview.attr('src', e.target.result);
                imagePreview.attr('hidden', false);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.select-2').select2();

});

$(document).on('click', '.remove-item-btn', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    var deleteModal = $("#deleteRecordModal");
    deleteModal.find('#delete-form').attr('action', url);

    try {
        jQuery.noConflict();
    } catch (e) {
    }

    try {
        deleteModal.modal('toggle');
    } catch (e) {
        $('.remove-item-btn').click();
    }
});

$('#category_id').on('change', function (e) {
    var categoryId = $(this).val();
    var agenciesSelect = $("#agency_id");
    $.ajax({
        type: 'GET', //THIS NEEDS TO BE GET
        url: '/dashboard/agencies-by-category/'+categoryId,
        success: function (data) {
            agenciesSelect.find('option').not(':first').remove();
            $.each(data, function() {
                agenciesSelect.append($("<option />").val(this.id).text(this.name));
            });
        },
        error: function(data) {
            console.log(data);
        }
    });

});

$(document).on('change', "#customSwitchsizelg",function (e) {
    $.ajax({
        type: 'POST',
        url: '/dashboard/update-status',
        data:{'name':$(this).attr('data-name'),'id':$(this).attr('data-id'),'status':$(this).is(':checked'),'col':$(this).attr('data-col'),
            '_token': $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
        },
        error: function(data) {
        }
    });
});

$(document).on('change', ".changeRecordStatus",function (e) {
    var Self = $(this);
    $.ajax({
        type: 'POST',
        url: Self.data('url'),
        data:{
            'id': Self.data('id'),
            'col': Self.data('col'),
            'status': Self.is(':checked'),
            '_token': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
        },
        error: function(data) {
            Self.prop('checked', !Self.is(':checked'));
        }
    });
});

$(document).on('change', "#is_paid",function (e) {
    if ($(this).prop('checked') === true) {
        $('.check_is_paid, .full_payment').prop('disabled', false);
    } else {
        $('.check_is_paid').prop('disabled', true).val('');
        $('.full_payment').prop('disabled', true).prop('checked', false);
        $('#delay_fees').val('').prop('disabled', true).prop('checked', false);
        $('#delay_date').val('').prop('disabled', true);
        $('#first_payment, #second_payment').prop('disabled', true).val('');
    }
});

$(document).on('change', "#two_payment",function (e) {
    if ($(this).prop('checked') === true) {
        $('#delay_fees').prop('disabled', false);
        $('#delay_date').prop('disabled', false);
        $('#first_payment, #second_payment').prop('disabled', false);

        changePrice();
    }
});

$(document).on('input', "#price",function () {
    if($('#two_payment').prop('checked') === true) {
        changePrice();
    }
});

$(document).on('input', "#first_payment",function () {
    var Self = $(this);
    if($('#two_payment').prop('checked') === true) {
        var Price = $('#price').val() == '' ? 0 : $('#price').val();
        Self.attr('max', Price);
        if(parseFloat(Self.val()) > parseFloat(Price)) {
            Self.val(Price);
        } else if(parseFloat(Self.val()) < 0 || Self.val() == '') {
            Self.val(0);
        }
        var SecondPayment = $('#second_payment');
        SecondPayment.val((Price - Self.val()).toFixed(2));
    }
});

$(document).on('input', "#second_payment",function () {
    var Self = $(this);
    if($('#two_payment').prop('checked') === true) {
        var Price = $('#price').val() == '' ? 0 : $('#price').val();
        Self.attr('max', Price);
        if(parseFloat(Self.val()) > parseFloat(Price)) {
            Self.val(Price);
        } else if(parseFloat(Self.val()) < 0 || Self.val() == '') {
            Self.val(0);
        }
        var FirstPayment = $('#first_payment');
        FirstPayment.val((Price - Self.val()).toFixed(2));
    }
});


function changePrice()
{
    var Price = $('#price').val();
    $('#first_payment').val(Price/2).prop('max', Price);
    $('#second_payment').val(Price/2).prop('max', Price);
}

function changePaymentsParts()
{
    var Price = $('#price').val();
    var FirstPayment = $('#first_payment');
    var SecondPayment = $('#second_payment');
}

$(document).on('change', "#full_payment",function (e) {
    if ($(this).prop('checked') === true) {
        $('#delay_fees').val('').prop('disabled', true).prop('checked', false);
        $('#delay_date').val('').prop('disabled', true);
        $('#first_payment, #second_payment').prop('disabled', true).val('');
    }
});

// Remove empty inputs name
$('#searchForm').submit(function () {
    $(this)
        .find('input[name], select[name]')
        .filter(function () {
            return !this.value;
        }).prop('name', '');
});



$('#select_permissions').click(function () {
    if($(this).prop('checked') === true) {
        $('.checked_permission, .selectBoxPermission').prop('checked', true);
    } else {
        $('.checked_permission, .selectBoxPermission').prop('checked', false);
    }
});

$(document).on('click', '.selectBoxPermission', function () {
    var Self = $(this);
    var Children = Self.parents('.accordion').find('.checked_permission');
    if(Self.prop('checked') === true) {
        Children.prop('checked', true);
    } else {
        Children.prop('checked', false);
    }
});

$('.disabled_inputs input, .disabled_inputs textarea, .disabled_inputs select').prop('disabled', true)
