function confirmDelete(type, title) {
    if (type == 'meeting') {
        result = confirm('Are you sure that you want to delete the meeting "' + title + '"?');
    }
    return result;
}