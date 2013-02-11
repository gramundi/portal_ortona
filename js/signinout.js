Event.observe(window,'load',function (e) {
    new Ajax.Autocompleter('autocomplete','nameList',url, {frequency: 0.2, afterUpdateElement: updateID});
    $('autocomplete').focus();
    //Effect.Appear($( 'nameList'), { duration: 1.5 });
});

function updateID(inputField,selectedItem) {
    $('person_id').value = selectedItem.id;
    $('submit').removeAttribute('disabled');
}