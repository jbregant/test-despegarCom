$.extend( $.fn.dataTable.defaults, {
    language: {
        "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
    },
    pagination: true,
});

$.extend($.validator.messages, {
    required: "Este campo es requerido.",
    remote: "Por favor corriga este campo.",
    email: "Ingrese un e-mail valido.",
    url: "Ingrese un URL valido.",
    date: "Ingrese una fecha valida.",
    equalscity: "Origen/Destino invalido."
});

$.validator.setDefaults({
    highlight: function(element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function(element) {
        $(element).removeClass('is-invalid');
        $(element).addClass('is-valid');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});

$.datepicker.setDefaults({
    dateFormat: "dd-mm-yy",
    minDate: new Date()
});

/**
 * create html option tag/s for combo select/s
 * @param data
 * @param action
 * @returns {string}
 */

function makeSelectOptions(data, action) {

    let optionsHtml = '<option value="">Seleccione una opción</option>';

    data.forEach(function (element) {
        switch (action) {
            case 1:
            case 2:
                optionsHtml += '<option value="' + element.id + '">'+element.city + ',' + element.state + ',' + element.country + '</option>';
                break;
            case 3:
                optionsHtml += '<option value="' + element.id + '">'+element.name + '</option>';
                break;
            default:
                break;
        }
    });

    return optionsHtml;
}

/**
 * check whether city of origin and destination are the same
 * @returns {boolean}
 */
function equalCityValidator(){

    let departureCityValue = $('#departure_city_id option:selected').val();
    let arrivalCityValue = $('#arrival_city_id option:selected').val();
    let departureCity = $('#departure_city_id');
    let arrivalCity = $('#arrival_city_id');
    let modalError = $('.modal_error');

    if(departureCityValue === arrivalCityValue){
        departureCity.addClass('is-invalid');
        arrivalCity.addClass('is-invalid');
        $('#modal_error_msg').text('Las ciudades Origen/Destino no pueden ser la misma.').css('color','red');
        modalError.addClass('is-invalid').show();
        setTimeout(function(){
            modalError.fadeOut( "slow");
        },2000);
    }

    return (departureCityValue === arrivalCityValue)
}

/**
 * initialize datatable
 */
function initTable() {
    dtReservations = $('#reservations_table').DataTable( {
        "ajax": __HOST__ + '/src/despegarComController.php?action=getTable'
    } );
}

/**
 * initialize handlers
 */
function initHandlers(){

    // datepicker initialization
    $('.datepicker').datepicker();

    // add reservation button
    $('.btn_add_reservation').on('click', function () {

        $('#reservation_form')[0].reset();

        // logic to fill combobox on the form
        let fillCombosData = [
            [1, 'getCities', 'departure_city_id'],
            [2, 'getCities', 'arrival_city_id'],
            [3, 'getAirlines', 'airline_id'],
        ];

        fillCombosData.forEach(function (element) {
            $.ajax({
                beforeSend: function() {
                    $('#' + element[2] +'').append('<option value=""><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>' +
                        '<span class="sr-only">Cargando...</span></option>');
                },
                method: "GET",
                url: __HOST__ + '/src/despegarComController.php?action=' + element[1],
                statusCode: {
                    400: function (response) {
                        alert('Bad request');
                    },
                    404: function (response) {
                        alert('Not found');
                    },
                    405: function (response) {
                        alert('Method not allowed');
                    },
                    500: function (response) {
                        alert('Internal server error');
                    }
                }, success: function (response) {
                    let optionsHtml = makeSelectOptions(response.data, element[0]);
                    $('#' + element[2] +'').html("").append(optionsHtml);
                },
            });
        });
    });

    // refresh table button
    $('.btn-table-refresh').on('click', function () {
        dtReservations.ajax.reload();
    });

    // modal accept button
    $('#dialog_acept').on('click', function () {
        let reservationsForm = $('#reservation_form');
        reservationsForm.validate({
            lang: 'es',
            rules: {
                'form[departure_city_id]': {
                    required: true,
                },
                'form[departure_date]': {
                    required: true,
                },
                'form[arrival_city_id]': {
                    required: true,
                },
                'form[arrival_date]': {
                    required: true,
                },
                'form[airline_id]': {
                    required: true,
                }
            }
        });

        // check validations
        if (!reservationsForm.valid()){
            return false;
        }
        // origin-destination check
        if(equalCityValidator()){
            return false;
        }

        let formData = {
            action: 'addReservation',
            departureCityId: $('#departure_city_id option:selected').val(),
            arrivalCityId: $('#arrival_city_id option:selected').val(),
            departureDate: $('#departure_date').val(),
            airlineId: $('#airline_id option:selected').val(),
        };

        $.ajax({
            method: "POST",
            url: __HOST__ + '/src/despegarComController.php',
            data: JSON.stringify(formData),
            statusCode: {
                200: function (response) {
                    alert('Reserva agregada correctamente');
                    $('#modal_reservations').modal('hide');
                    dtReservations.ajax.reload();
                },
                400: function (response) {
                    alert('Bad request');
                },
                404: function (response) {
                    alert('Not found');
                },
                405: function (response) {
                    alert('Method not allowed');
                },
                500: function (response) {
                    alert('Internal server error');
                }
            }
        });
    });
}

$(document).ready(function () {
    initTable();

    initHandlers();
});