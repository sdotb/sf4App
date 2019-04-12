$(document).ready(function () {
    (function () {
        //var $tagInput = $('input[name$="[tagsText]"]');
        function tags($input) {
            $input.attr('type', 'hidden').select2({
                tags: true,
                tokenSeparators: [","],
                createSearchChoice: function(term, data) {
                    if ($(data).filter(function () {
                        return this.text.localeCompare(term) === 0;
                    }).length === 0) {
                        return {
                            id: term,
                            text: term
                        };
                    }
                },
                multiple: true,
                ajax: {
                    url: $input.data('ajax'),
                    dataType: "json",
                    data: function (term, page) {
                        return {
                            q: term
                        };
                    },
                    results: function (data, page) {
                        return {
                            results: data
                        };
                    }
                },
                initSelection: function (element, callback) {
                    var data = [];
                    function splitVal(string, separator) {
                        var val, i, l;
                        if (string === null || string.length < 1) {
                            return [];
                        }
                        val = string.split(separator);
                        for (i = 0, l = val.length; i < l; i = i + 1) {
                            val[i] = $.trim(val[i]);
                        }
                        return val;
                    }
                    $(splitVal(element.val(), ",")).each(function () {
                        data.push({
                            id: this,
                            text: this
                        });
                    });
                    callback(data);
                }
            });
        }
        if ($tagInput.length > 0) {
            tags($tagInput);
        }
    }());
});