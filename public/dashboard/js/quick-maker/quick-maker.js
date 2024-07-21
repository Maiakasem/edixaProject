$(function() {
    'use strict';
    $("select[name='is_module_child']").change(function(e) {
        e.preventDefault();
        if($(this).val() === 1 || $(this).val() === "1") {
            $("select[name='module']").prop('disabled', false);
        } else {
            $("select[name='module']").prop('disabled', true);
        }
    });
    $(document).on("change",".relationFinder", function(e) {
        if($(this).val() === 1 || $(this).val() === "1") {
            $(this).parent().parent().find('.relation_model').prop('disabled', false);
            $(this).parent().parent().find('.relation_key').prop('disabled', false);
        } else {
            $(this).parent().parent().find('.relation_model').prop('disabled', true);
            $(this).parent().parent().find('.relation_key').prop('disabled', true);
        }
    });
    $(document).on('change', '.validationSelector', function() {
        let validations = $(this).find('option:selected').data('validation');
        if(validations !== "" && validations != null) {
            $(this).next('.validations').find('.checkboxes').empty();
            $(this).next('.validations').find('.mustInputs .row').empty();
            let mustInput = ['max', 'min', 'mimes', 'between'];
            validations.forEach((validation) => {
                let inputName   = $(this).attr('name');
                inputName       = inputName.replace('blade_type', 'validations');
                inputName       = inputName+'['+validation+']';
                if ($.inArray(validation, mustInput) !== -1) {
                    $(this).next('.validations').find('.mustInputs .row').append( '<div class="col-lg"><div class="input-group"> <span class="input-group-text" style="width: 113px;" id="basic-addon11"> <label class="switch switch-primary"><input type="checkbox" data-validation="'+validation+'" class="switch-input ValidationInSwitches"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span><span class="switch-label">'+validation+'</span></label> </span> <input disabled data-validation="'+validation+'" type="text" class="form-control ValidationSwitchInput" placeholder="'+validation+'" aria-label="'+validation+'" aria-describedby="basic-addon11"> </div></div>');
                } else {
                    $(this).next('.validations').find('.checkboxes').append('<label class="switch switch-primary"><input data-validation="'+validation+'" type="checkbox" class="ValidationSwitches switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span><span class="switch-label">'+validation+'</span></label>');

                }
            });
        }
    });
    $(document).on("click",".ValidationInSwitches", function(e) {
        let ValidationSwitchInput = $(this).parent().parent().parent().find('.ValidationSwitchInput');
        if($(this).is(':checked')) {
            ValidationSwitchInput.prop('disabled', false);
        } else {
            ValidationSwitchInput.prop('disabled', true);
        }
    });
});










let QuickBuilderForm = document.querySelector('.QuickBuilderForm')
document.querySelector('.SubmitQuickBuilderForm').addEventListener('click', (buttonEve) => {
    buttonEve.preventDefault();
    let validations = [];
    document.querySelectorAll('.repeater-item').forEach((item) => {
        let data = [];
        let checkboxes = [];
        let type = [];
        let identifier = item.querySelector('.identifier').value;

        item.querySelectorAll('.ValidationSwitches').forEach(function (box) {
            if(box.checked) {
                checkboxes.push(box.dataset.validation);
            }
        });
        item.querySelectorAll('.ValidationInSwitches').forEach(function (box) {
            if(box.checked) {
                let validationRule = box.dataset.validation;
                type.push({[validationRule]: box.parentElement.parentElement.parentElement.querySelector('.ValidationSwitchInput').value });
            }
        });
        data.push(checkboxes);
        data.push(type);
        validations.push({[identifier]: data});
    });
    console.log(validations);
    document.querySelector('#ValidationsData').value = JSON.stringify(validations);
    setTimeout(() => {
        QuickBuilderForm.submit();
    }, 500);
});
