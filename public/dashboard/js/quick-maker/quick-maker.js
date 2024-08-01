// Start Class Data
let className           = document.querySelector('#className');
let is_module_child     = document.querySelector('#is_module_child');
let moduleIn            = document.querySelector('#module');
let errorAlert          = document.querySelector('.errorAlert');
let ClassData           = [];
let columns             = [];
// End Class Data


// Start Database
let DatabaseObj         = [];
let DatabaseName        = document.querySelector('#DatabaseName');
let DatabaseValue       = document.querySelector('#DatabaseValue');
let DatabaseType        = document.querySelector('#DatabaseType');

// End Database

// Start Spacial Data
let spacialObj          = [];
let searchable          = document.querySelector('#searchable');
let translatable        = document.querySelector('#translatable');
// End Spacial Data

// Start Relationship Data
let relationshipObj     = [];
let relation            = document.querySelector('#relation');
let relation_model      = document.querySelector('#relation_model')
let relation_key        = document.querySelector('#relation_key');
let relation_display    = document.querySelector('#relation_display');
// End Relationship Data

// Start Validation Data
let validationObj       = [];



// End Validation Data


let SendToDatabase = document.querySelector('.SendToDatabase');
SendToDatabase.addEventListener('click', (e) => {
    e.preventDefault();
    if(className.value[0] != "" && className.value != null) {
        ClassData.push({"className":className.value});

        let is_module_child_status = (is_module_child.value == 0) ? false : true;

        
        ClassData.push({"is_module_child":is_module_child_status});
        ClassData.push({"module":moduleIn.value});
        ClassData.push({"columns":columns});

        document.querySelector("#formData").value = JSON.stringify(ClassData);
        setTimeout(() => {
            e.target.parentElement.submit();
        }, 500);
    } else {
        errorAlert.innerHTML = "Class Name is required";

    }

});

// Start Add Column Data
let AddColumn   = document.querySelector('.AddColumn');
AddColumn.addEventListener('click', (e) => {
    let column = [];
    if(DatabaseName.value[0] != "" && DatabaseName.value != null) {
        if(DatabaseValue.value[0] != "" && DatabaseValue.value != null) {


            DatabaseObj.push({"columnName":DatabaseName.value});
            DatabaseObj.push({"columnValue":DatabaseValue.value});
            DatabaseObj.push({"columnType":DatabaseType.value});

            column.push({"database":DatabaseObj});

            DatabaseObj = [];

            let searchableStatus = (searchable.value == 0) ? false : true;
            let translatableStatus = (translatable.value == 0) ? false : true;
            
            spacialObj.push({"searchable": searchableStatus});
            spacialObj.push({"translatable": translatableStatus});

            column.push({"spacial":spacialObj});

            spacialObj = [];


            let relationStatus = (relation.value == 0) ? false : true;
            relationshipObj.push({"relation" : relationStatus});
            if(relationStatus) {
                relationshipObj.push({"relation_model" : relation_model.value});
                relationshipObj.push({"relation_key" : relation_key.value});
                relationshipObj.push({"relation_display" : relation_display.value});
                
            } else {
                relationshipObj.push({"relation_model" : null});
                relationshipObj.push({"relation_key" : null});
                relationshipObj.push({"relation_display" : null});
            }

            column.push({"relationship":relationshipObj});
            relationshipObj = [];
            //////////////////////
            /////////////////////
            let ValidationSwitches  = document.querySelectorAll('.ValidationSwitches');
            let validationSelector  = document.querySelector('.validationSelector');
            let validationInputs    = document.querySelectorAll('.mustInputs .input-group .ValidationInSwitches');
            /////////////////////

            validationObj.push({"blade_type" : validationSelector.value});
            let validationArr = [];
            ValidationSwitches.forEach((switchInp) => {
                if (switchInp.checked) {
                    validationArr.push(switchInp.dataset.validation)
                    //switchInp.value  = '';
                }
            });
            validationObj.push({"checkboxes" : validationArr});
            validationArr = [];
            validationInputs.forEach((switchInp) => {
                if (switchInp.checked) {
                    console.log(switchInp.parentElement.parentElement.parentElement.querySelector('.ValidationSwitchInput').value);
                    validationArr.push({ [switchInp.dataset.validation]: switchInp.parentElement.parentElement.parentElement.querySelector('.ValidationSwitchInput').value });
                }
            });
            validationObj.push({"values" : validationArr});
            validationArr = [];
            column.push({"validations" : validationObj});




            columns.push(column);









            let col = document.createElement('div');
            col.className = "col-lg-3";

            let card = document.createElement('div');
            card.className = "card";


            let cardBody = document.createElement('div');
            cardBody.className = "card-body";

            let listGroup = document.createElement('ul');
            listGroup.className = "list-group";




            let columnNameListItem = document.createElement('li');
            columnNameListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            columnNameListItem.appendChild(document.createTextNode("columnName: " + DatabaseName.value));

            let columnValueListItem = document.createElement('li');
            columnValueListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            columnValueListItem.appendChild(document.createTextNode("columnValue: " + DatabaseValue.value));

            let columnTypeListItem = document.createElement('li');
            columnTypeListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            columnTypeListItem.appendChild(document.createTextNode("columnType: " + DatabaseType.value));

            let searchableListItem = document.createElement('li');
            searchableListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            searchableListItem.appendChild(document.createTextNode("searchable: " + searchableStatus));

            let translatableListItem = document.createElement('li');
            translatableListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            translatableListItem.appendChild(document.createTextNode("translatable: " + translatableStatus));

            let relationListItem = document.createElement('li');
            relationListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            relationListItem.appendChild(document.createTextNode("relation: " + relationStatus));

            let relation_modelListItem = document.createElement('li');
            relation_modelListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            relation_modelListItem.appendChild(document.createTextNode("relation_model: " + relation_model.value));

            let relation_keyListItem = document.createElement('li');
            relation_keyListItem.className = "list-group-item d-flex justify-content-between align-items-center";
            relation_keyListItem.appendChild(document.createTextNode("relation_key: " + relation_key.value));

            listGroup.appendChild(columnNameListItem);
            listGroup.appendChild(columnValueListItem);
            listGroup.appendChild(columnTypeListItem);
            listGroup.appendChild(searchableListItem);
            listGroup.appendChild(translatableListItem);
            listGroup.appendChild(relationListItem);
            listGroup.appendChild(relation_modelListItem);
            listGroup.appendChild(relation_keyListItem);

            cardBody.appendChild(listGroup);
            card.appendChild(cardBody);
            col.appendChild(card);
            document.querySelector('.ListGroupContainer').appendChild(col);

            DatabaseName.value      = '';
            DatabaseValue.value     = '';
            DatabaseType.value      = '';
            searchable .value       = '';
            translatable.value      = '';
            relation.value          = '';
            relation_model.value    = '';            
            relation_key.value      = '';
            relation_display.value  = '';




        } else {
            errorAlert.innerHTML = "Column Value is required";
        }
    } else {
        errorAlert.innerHTML = "Column Name is required";
    }
});
// End Add Column Data





is_module_child.addEventListener('change', function(e) {
    if(e.target.value == 1) {
        moduleIn.removeAttribute('disabled');
    } else {
        moduleIn.setAttribute('disabled', 'disabled');
    }
});

relation.addEventListener('change', function(e) {
    if(e.target.value == 1) {
        relation_model.removeAttribute('disabled');
        relation_key.removeAttribute('disabled');
        relation_display.removeAttribute('disabled');
    } else {
        relation_model.setAttribute('disabled', 'disabled');
        relation_key.setAttribute('disabled', 'disabled');
        relation_display.setAttribute('disabled', 'disabled');
    }
});


$(function() {
    'use strict';
    
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
                console.log(inputName);

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
