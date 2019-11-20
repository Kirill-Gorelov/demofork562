jsBackend.imscevents =
    {
        // init
        init: function()
        {
            /** Инициализация автодополнения при вводе */
            jsBackend.imscevents.initAutoComplete();

            /** Нажатие на кнопку обновления справочников */
            $("a.runparserdit").click(function(e){
                jsBackend.imscevents.disableBnts();
                jsBackend.imscevents.runparserdit();
                e.preventDefault;
            });

            /** Нажатие на кнопку обновления данных из шлюза ДНПП */
            $('a.dnpp-import').click(function(e) {
                jsBackend.imscevents.disableBnts();
                jsBackend.imscevents.dnppImport();
                e.preventDefault();
            });

            /** Обработчик нажатия на кнопку испорта из шлюза ICT */
            $('a.ict-import').click(function() {
                jsBackend.imscevents.disableBnts();
                jsBackend.imscevents.ictImport();
            });

            /** Нажатие на кнопку удаления мероприятий, загруженных из ДНПП */
            $('a.delete-dnpp-events').click(function(e) {
                jsBackend.imscevents.deleteDnppEvents();
                e.preventDefault();
            });

            /** Нажатие на кнопку удаления мероприятий, загруженных из ICT */
            $('a.delete-ict-events').click(function(e) {
                jsBackend.imscevents.deleteIctEvents();
                e.preventDefault();
            });

            jsBackend.imscevents.updatePreview();

            /** Автообновление предварительного просмотра мероприятия */
            $('#title,#image,#site,#address,#type,#date,#time').change(function() {
                jsBackend.imscevents.updatePreview();
            });

            $('#title,#site,#address').keyup(function () {
                jsBackend.imscevents.updatePreview();
            });

            /** Обновление предпросмотра мероприятия при изменениях в CKEditor */
            for (let editorIndex in CKEDITOR.instances) {
                CKEDITOR.instances[editorIndex].on('change', function() {
                    jsBackend.imscevents.updatePreview();
                });
            }
        },

        /*
        Деактивируем кнопки
         */
        disableBnts: function() {
            $("a.runparserdit").addClass("disabled");
            $("a.dnpp-import").addClass("disabled");
            $('a.ict-import').addClass('disabled');
        },

        /*
        Активируем кнопки
         */
        enableBtns: function() {
            $("a.runparserdit").removeClass("disabled");
            $("a.dnpp-import").removeClass("disabled");
            $('a.ict-import').removeClass('disabled');
        },

        /*
        Запрос на обновление справочников
         */
        runparserdit: function()
        {
            $.ajax(
                {
                    data:
                        {
                            fork: { action: 'Parserdit' }
                        },
                    success: function(data, textStatus)
                    {
                        if(data.code == 200)
                        {
                            jsBackend.imscevents.enableBtns();
                            location.reload();
                        }
                        else
                        {
                            jsBackend.messages.add('danger', 'alter sequence failed.');
                        }
                    },
                    timeout: 600000
                });
        },

        /** Признак того, что выполняется обновление справочников из шлюза ДНПП */
        updateInProgress: false,

        executionStarted: false,

        /**
         * Загрзука событий из шлюза ДНПП
         */
        dnppImport: function() {
            $.ajax({
                data: { fork: { action: 'DnppImport' }},
                success: function (data) {
                    if (data.code == 200) {
                        jsBackend.imscevents.enableBtns();
                        location.reload();
                    } else {
                        jsBackend.messages.add('danger', 'alter sequence failed.');
                    }
                },
                complete: function() {
                    jsBackend.imscevents.updateInProgress = false;
                    $('#dnpp-update-status').addClass('hidden');
                },

                timeout: 600000
            });

            jsBackend.imscevents.updateInProgress = true;
            jsBackend.imscevents.updateStatus();
        },

        /** Загрузка мероприятий из шлюза ДИТ */
        ictImport: function() {
            $.ajax({
                data: { fork: { action: 'IctImport' }},
                success: function (data) {
                    if (data.code == 200) {
                        jsBackend.imscevents.enableBtns();
                        location.reload();
                    } else {
                        jsBackend.messages.add('danger', 'alter sequence failed.');
                    }
                },
                complete: function() {
                    jsBackend.imscevents.updateInProgress = false;
                    $('#dnpp-update-status').addClass('hidden');
                    jsBackend.imscevents.enableBtns();
                },

                timeout: 600000
            });

            jsBackend.imscevents.updateInProgress = true;
            jsBackend.imscevents.updateStatus();
        },

        /**
         * Выполняет получение и отображение текущего статуса обновления мероприятий из шлюза ДНПП
         */
        updateStatus: function () {
            if (!jsBackend.imscevents.updateInProgress) return;

            let $currentStep = $('.dnpp-step-number');
            let $totalSteps = $('.dnpp-total-steps');
            let $progressBar = $('.dnpp-progress-bar');

            $.ajax({
                data: { fork: { action: 'Status' }},
                success: function (data) {
                    $currentStep.html(data.data.current_step);
                    $totalSteps.html(data.data.total_step_count);

                    let progressValue = (data.data.total_news_count == 0) ? 0 : data.data.current_news / data.data.total_news_count * 100;
                    $progressBar.val(progressValue);

                    $('#dnpp-update-status').removeClass('hidden');

                    // FIXME: Костыли - обход заваливания Ajax c 504 ошибкой
                    if (data.data.current_step == data.data.total_step_count
                        && data.data.current_news == data.data.total_news_count) {
                        if (jsBackend.imscevents.executionStarted) {
                            location.reload();
                        }
                    } else {
                        jsBackend.imscevents.executionStarted = true;
                    }
                },
                complete: function () {
                    setTimeout(function() {
                        jsBackend.imscevents.updateStatus();
                    }, 10000);
                },
                timeout: 600000
            });
        },

        deleteDnppEvents: function () {
            $.ajax({
                data: { fork: { action: 'DeleteDnppEvents' }},
                success: function () {
                    jsBackend.messages.add('success', 'Мероприятия, загруженные из ДНПП, успешно удалены');
                },
                error: function () {
                    jsBackend.messages.add('danger', 'Ошибка удаления мероприятий, загруженных из ДНПП');
                },
                timeout: 60000
            });
        },

        deleteIctEvents: function () {
            $.ajax({
                data: { fork: { action: 'DeleteIctEvents' }},
                success: function () {
                    jsBackend.messages.add('success', 'Мероприятия, загруженные из ICT, успешно удалены');
                },
                error: function () {
                    jsBackend.messages.add('danger', 'Ошибка удаления мероприятий, загруженных из ICT');
                },
                timeout: 60000
            });
        },

        /** Настройка движка автодополнения */
        getSearchEngine: function() {
            return new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: $.ajaxSettings.url,
                    prepare: function (query, settings) {
                        settings.type = 'POST';
                        settings.data = {
                            fork: {
                                module: 'Imscevents',
                                action: 'GetCompanyList',
                                language: jsBackend.current.language
                            },
                            term: query,
                            innoObjectTypeId: $('#innoObjectTypeId').val()
                        }

                        return settings;
                    },
                    filter: function (searchResults) {
                        return $.map(searchResults.data, function (result) {
                            return {
                                id: result.id,
                                name: result.name
                            }
                        });
                    }
                }
            });
        },

        /** Настройка автодополнения */
        initAutoComplete: function () {
            let searchEngine = jsBackend.imscevents.getSearchEngine();
            let $oiiName = $('#oiiName');
            let $oiiId = $('#oiiId');

            /** Инициализация автодополнения наименования компании */
            let initTypeAhead = function() {
                $($oiiName).typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 0
                }, {
                    name: 'companies',
                    source: searchEngine,
                    display: 'name',
                    limit: 10000,
                    templates: {
                        suggestion: function (data) {
                            return '<strong>' + data.id + ' - ' + data.name + '</strong>';
                        }
                    }
                }).bind('typeahead:select', function (ev, suggestion) {
                    $oiiId.val(suggestion.id);
                });
            };
            initTypeAhead();

            // При изменении выбранного типа компании реинициализируем автодополнение
            $('#innoObjectTypeId').change(function () {
                $oiiName.typeahead('destroy');
                initTypeAhead();
            });

            /** Автозаполнение названия компании при ручном вводе ее кода */
            $($oiiId).change(function (evt) {
                let $oii_id = $oiiId.val();
                if ($oii_id == '') {
                    $oiiName.val('');
                    return;
                }

                $.ajax({
                    data: {
                        fork: {
                            module: 'Imscevents',
                            action: 'GetCompany',
                            language: jsBackend.current.language
                        },
                        oii_id: $oii_id
                    },
                    success: function (data) {
                        $oiiName.val(data.data.name);
                        if (data.data.innoObjectTypeId != '0')
                            $('#innoObjectTypeId').val(data.data.innoObjectTypeId);
                    }
                })
            })
        },

        /** Обновление карточки предварительного просмотра мероприятия */
        updatePreview: function() {
            $('#eventTitle').html($('#title').val());
            $('#eventShort').html($('#short').val());
            $('#eventDescription').html($('#description').val());

            let imagePath = $('#image').val();
            if (imagePath) {
                let sourceStart = imagePath.indexOf('/src');
                if (sourceStart != -1)
                    imagePath = imagePath.substring(sourceStart);
            }
            $('#eventImage a').prop('href', imagePath);
            $('#eventImage img').prop('src', imagePath);

            let site = $('#site').val().trim();
            if (site) {
                if (site.indexOf('http') == -1)
                    site = 'http://' + site;
            }
            if (site != '') {
                $('#eventSite').show();
                $('#eventSite a').prop('href', site).html(site);
            } else {
                $('#eventSite').hide();
            }

            let address = $('#address').val().trim();
            if (address != '') {
                $('#eventAddress').html(address);
                $('#eventAddress').show();
            } else {
                $('#eventAddress').hide();
            }

            $('#eventFormat').html($('#type option:selected').text());

            $('#eventDate').html(jsBackend.imscevents.formatEventDate($('#date').val()));

            let time = $('#time').val().trim();
            if (time != '') {
                $('#eventTime').html(time);
                $('#eventTime').show();
            } else {
                $('#eventTime').hide();
            }
        },

        /** Форматирование даты мероприятия */
        formatEventDate: function (date) {
            if (!date) return '';
            if (date.length != 10) return '';

            let day = date.substr(0, 2);
            let month = parseInt(date.substr(3, 2));

            if (!(month >= 1 && month <= 12)) return '';

            let monthes = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
                'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

            return day + ' ' + monthes[month - 1] ;
        }
    };

$(jsBackend.imscevents.init);
