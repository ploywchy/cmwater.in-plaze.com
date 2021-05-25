/**
 * Create Date/Time Picker (for PHPMaker 2021)
 * @license Copyright (c) e.World Technology Limited. All rights reserved.
 */

// Global options
ew.dateTimePickerOptions = {
    keepInvalid: true // Avoid Tempus Dominus bug
};

// Create date/time picker
ew.createDateTimePicker = function(formid, id, options) {
    if (id.includes("$rowindex$"))
        return;
    var $ = jQuery,
        el = ew.getElement(id, formid),
        sv = ew.getElement("sv_" + id, formid), // AutoSuggest
        $input = $(sv || el),
        format = "",
        useShortTime = ew.DATETIME_WITHOUT_SECONDS;
    if (!el || $input.data("DateTimePicker") || $input.parent().data("DateTimePicker"))
        return;
    var _getDateTimeFormatId = function(id, withtime) {
        if (id == 5 || id == 9)
            return withtime ? 9 : 5;
        else if (id == 6 || id == 10)
            return withtime ? 10 : 6;
        else if (id == 7 || id == 11)
            return withtime ? 11 : 7;
        else if (id == 12 || id == 15)
            return withtime ? 15 : 12;
        else if (id == 13 || id == 16)
            return withtime ? 16 : 13;
        else if (id == 14 || id == 17)
            return withtime ? 17 : 14;
        return id;
    };
    options = Object.assign({}, ew.dateTimePickerOptions, options);
    var formatid = options.format;
    if (formatid > 100) {
        formatid -= 100;
        useShortTime = true;
    }
    if (formatid == 0)
        formatid = ew.DATE_FORMAT_ID;
    else if (formatid == 1)
        formatid = _getDateTimeFormatId(ew.DATE_FORMAT_ID, true);
    else if (formatid == 2)
        formatid = _getDateTimeFormatId(ew.DATE_FORMAT_ID, false);
    switch (formatid) {
        case 5: format = "YYYY/MM/DD"; break;
        case 6: format = "MM/DD/YYYY"; break;
        case 7: format = "DD/MM/YYYY"; break;
        case 9: format = "YYYY/MM/DD HH:mm" + (useShortTime ? "" : ":ss"); break;
        case 10: format = "MM/DD/YYYY HH:mm" + (useShortTime ? "" : ":ss"); break;
        case 11: format = "DD/MM/YYYY HH:mm" + (useShortTime ? "" : ":ss"); break;
        case 12: format = "YY/MM/DD"; break;
        case 13: format = "MM/DD/YY"; break;
        case 14: format = "DD/MM/YY"; break;
        case 15: format = "YY/MM/DD HH:mm" + (useShortTime ? "" : ":ss"); break;
        case 16: format = "MM/DD/YY HH:mm" + (useShortTime ? "" : ":ss"); break;
        case 17: format = "DD/MM/YY HH:mm" + (useShortTime ? "" : ":ss"); break;
    }
    format = format.replace(/\//g, ew.DATE_SEPARATOR).replace(/:/g, ew.TIME_SEPARATOR);
    options.format = format;
    if (!options.locale) // locale
        options.locale = ew.LANGUAGE_ID.toLowerCase();
    var inputGroup = $.isBoolean(options.inputGroup) ? options.inputGroup : true;
    delete(options.inputGroup);
    options.debug = options.debug || ew.DEBUG;
    var args = {"id": id, "form": formid, "enabled": true, "inputGroup": inputGroup, "options": options};
    $(function() {
        $(document).trigger("datetimepicker", [args]);
        if (!args.enabled)
            return;
        if (args.inputGroup !== false) {
            // <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
            // 	<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
            // 	<div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
            // 		<div class="input-group-text"><i class="fa fa-calendar"></i></div>
            // 	</div>
            // </div>
            var $textbox = $input,
                id = "datetimepicker_" + formid + $input.attr("id");
                $btn = $('<button class="btn btn-default" type="button"><i class="far fa-calendar-alt"></i></button>')
                    .on("click", function() {
                        $textbox.removeClass("is-invalid");
                    });
            $input.addClass("datetimepicker-input").attr("data-target", "#" + id)
                .wrap('<div class="input-group date" id="' + id + '" data-target-input="nearest"></div>')
                .after($('<div class="input-group-append" data-target="#' + id + '" data-toggle="datetimepicker"></div>').append($btn))
                .on("focus", function() {
                    $textbox.tooltip("hide").tooltip("disable");
                }).on("blur", function() {
                    $textbox.tooltip("enable");
                });
            $input = $input.parent().on("change.datetimepicker", function(e) {
                if (e.date) {
                    el.value = e.date.format(args.options.format);
                    el.dispatchEvent(new Event("change"));
                }
            });
        } else {
            // <input type="text" class="form-control datetimepicker-input" id="datetimepicker5" data-toggle="datetimepicker" data-target="#datetimepicker5"/>
            $input.addClass("datetimepicker-input").attr({ "data-toggle": "datetimepicker", "data-target": "#" + $input.attr("id") })
                .on("change.datetimepicker", function(e) {
                    if (e.date)
                        el.value = e.date.format(args.options.format);
                }).on("focus", function() {
                    $input.tooltip("hide").tooltip("disable");
                }).on("blur", function() {
                    $input.tooltip("enable");
                });
        }
        if (args.options.locale && moment.locale() != args.options.locale) {
            loadjs(ew.PATH_BASE + "moment/locale/" + args.options.locale + ".js", function() {
                moment.localeData().postformat = function(string) { return string }; // overwrite the postformat() in <locale.js>
                moment.updateLocale(args.options.locale, {
                    invalidDate: ew.language.phrase("InvalidDate")
                });
                $input.datetimepicker(args.options);
            });
        } else {
            moment.updateLocale(args.options.locale, {
                invalidDate: ew.language.phrase("InvalidDate")
            });
            $input.datetimepicker(args.options);
        }
    });
}
