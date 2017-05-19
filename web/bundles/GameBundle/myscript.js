$(function () {
    var minNumber = 1;
    var maxNumber = 6;
    var dice = $('.dice');

    var cumulDiceGamerArray = [];
    var len = $('.stats').length;
    for (i = 0; i < len; i++) {
        cumulDiceGamerArray[i] = 0;
    }

    var firstTime = true;
    var pathValidAnswerCurrent;
    var randomNumber;
    var bonusDifficulty;
    var finalScore;
    var bestScore;
    var gamerId;
    var exprUrl;
    // var index = 1;

    dice.children().hide();

    var rowGamer = Math.floor(Math.random() * len);
    var randomGamer = $('.stats:nth(' + rowGamer + ') .panel-title em').text();
    $('.messageInfo strong').html(randomGamer);

    $('.stats').each(function (i) {
        $(this).children('.pion').addClass('activeCase' + i);
    })

    $('.buttonDice').on('click', function (e) {
        e.preventDefault();

        $('.stats').each(function () {
            var that = $(this);
            if (that.children().children().children().children('em').text() == randomGamer) {
                gamerId = that.children().children().children('p:last').text();
            }
        })

        randomNumber = Math.floor(Math.random() * maxNumber) + minNumber;
        dice.children().hide();
        $('.dice .die' + randomNumber).show();

        rowGamer = Game(rowGamer, 'activeCase' + rowGamer);        
    })

    function Game(rowGamer, activeCase) { 
        var cumulDiceGamer = cumulDiceGamerArray[rowGamer] + randomNumber; 

        if (cumulDiceGamer > 64) {
            if (rowGamer == len - 1) {
                rowGamer = 0;
            } else {
                rowGamer++;
            }
            randomGamer = $('.stats:nth(' + rowGamer + ') .panel-title em').text();
            $('.messageInfo').html('Passe ton tour !').fadeIn().delay(2000).fadeOut().queue(function () {
                $(this).html('<strong>' + randomGamer + '</strong>, <br/> c\'est maintenant à ton tour.').fadeIn().dequeue();
            });
            $('.stats .panel-collapse').removeClass('in');

        } else if (cumulDiceGamer == 64) {
            $('.board td').removeClass(activeCase);
            $('.board .win').addClass(activeCase);
            $('.buttonDice').addClass('disabled');
            $('.stats .panel-collapse').addClass('in');

            var txtWin = 'Bravo ! <br/>Tu as fini la partie en premier. <br/>Tu bénéficies d\'un bonus de 30 points.<br/>';
            var scoreWinner = Math.max($('.stats:nth(0) .panel-title span').text(), $('.stats:nth(1) .panel-title span').text(), $('.stats:nth(2) .panel-title span').text());
            var winnerName = '';
            $('.stats .panel-title').each(function () {
                if ($(this).children().children('span').text() == scoreWinner) {
                    winnerName = $(this).children().children('em').text();
                }
            })
            var winner = 'Le gagnant de la partie est <strong>' + winnerName + '</strong, avec un score de ' + scoreWinner + ' points.<br/>';
            $('.messageInfo').html(txtWin).append(winner).fadeIn();

            finalScore = parseInt($('.stats:nth(' + rowGamer + ') .panel-title span').text());
            bestScore = parseInt($('.stats:nth(' + rowGamer + ') .panel-body p:nth(1) span').text());
            finalScore += 30;
            $('.stats:nth(' + rowGamer + ') .panel-title span').html(finalScore);
            var gameWonNb = parseInt($('.stats:nth(' + rowGamer + ') p:nth(2) span').text());
            var cumulScore = parseInt($('.stats:nth(' + rowGamer + ') p:nth(0) span').text());
            var level = parseInt($('.stats:nth(' + rowGamer + ') p:nth(4) span').text());
            var exprStats = /(^\D+)\d+\/\d+\/\d+\/\d+\/\d+\/\d+/;
            pathStatsCurrent = pathStats.replace(exprStats, '$1' + finalScore + '/' + bestScore + '/' + gamerId + '/' + gameWonNb + '/' + cumulScore + '/' + level);

            $.ajax({
                url: pathStatsCurrent,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    $('.stats:nth(' + rowGamer + ') p:nth(0) span').html(response.cumulScore);
                    $('.stats:nth(' + rowGamer + ') p:nth(1) span').html(response.bestScore);
                    $('.stats:nth(' + rowGamer + ') p:nth(2) span').html(response.gameWonNb);
                    $('.stats:nth(' + rowGamer + ') p:nth(4) span').html(response.level);
                },
                error: function () {
                    alert('erreur score');
                }
            });

        } else {
            $('.board td').each(function () {
                $(this).removeClass(activeCase);
                $(this).removeClass('multipleGamers');
                var that = $(this);

                if ($(this).text() == cumulDiceGamer) {
                    cumulDiceGamerArray[rowGamer] = cumulDiceGamer;
                    $(this).addClass(activeCase);
                    $('.questions').show();

                    if ($(this).text() % 4 == 0) {
                        ajaxRandomQuestion(url0, 'red');

                    } else if ($(this).text() % 4 == 1) {
                        ajaxRandomQuestion(url1, 'blue');

                    } else if ($(this).text() % 4 == 2) {
                        ajaxRandomQuestion(url2, 'yellow');

                    } else if ($(this).text() % 4 == 3) {
                        ajaxRandomQuestion(url3, 'green');
                    }
                }
                var listOfCumulDiceGamers = cumulDiceGamerArray.filter(function (cumul) {
                    return that.text() == cumul && cumul != 0;
                });
                if (listOfCumulDiceGamers.length > 1) {
                    that.addClass('multipleGamers');
                }

            })
            $('#modal').delay(1000).fadeIn('slow');
        }
        return rowGamer;
        
    }

    function ajaxRandomQuestion(urlType, color) {
        $('.infoAnswer').html('');

        exprUrl = /(^\D+\/\d+\/)\d+/;
        urlTypeCurrent = urlType.replace(exprUrl, '$1' + gamerId);

        $.ajax({
            url: urlTypeCurrent,
            type: 'GET',
            dataType: 'json',
            success: function (response) {

                if (!firstTime) {
                    var classStr = $('.questions').attr('class');
                    var lastClass = classStr.substr(classStr.lastIndexOf(' ') + 1);
                    $('.questions').removeClass(lastClass);
                }
                $('.questions').addClass(color);
                $('.questions .question').html(response.question);
                $('.infoQuestion .difficulty').html(response.difficulty);
                $('.infoQuestion .topic').html(response.topic.nameTopic);
                $('.infoQuestion .subject').html(response.topic.subject.nameSubject);
                $('.infoQuestion .schoolLevel').html(response.schoolClass.schoolClass);
                $('.questions .answers').html('');
                var index = 0;
                response.answers.forEach(function (data) {
                    $('.questions .answers').append('<div id="' + data.id + '" class="answer answer' + index + '">' + data.answer + '</div>');
                    index++;
                })

                var parent = $('.questions .answers');
                var divsAnswer = parent.children();
                while (divsAnswer.length) {
                    parent.append(divsAnswer.splice(Math.floor(Math.random() * divsAnswer.length), 1));
                }
                firstTime = false;

                if (response.difficulty == 'facile') {
                    bonusDifficulty = 5;
                } else if (response.difficulty == 'moyen') {
                    bonusDifficulty = 10;
                } else if (response.difficulty == 'difficile') {
                    bonusDifficulty = 15;
                }

                $('div.answer').on('click', function () {
                    var expr = /(^\D+)\d+\/\d+\/\d+\/\d+\/\d+/;
                    var score = parseInt($('.stats:nth(' + rowGamer + ') .panel-title span').text());
                    pathValidAnswerCurrent = pathValidAnswer.replace(expr, '$1' + $(this).attr('id') + '/' + gamerId + '/' + randomNumber + '/' + score + '/' + bonusDifficulty);
                    ajaxValidAnswer(pathValidAnswerCurrent, $(this));
                })

            },
            error: function () {
                alert('erreur');
            }
        });
    }


    function ajaxValidAnswer(path, that) {
        $.ajax({
            url: path,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                $('.stats:nth(' + rowGamer + ') p:nth(3) span').html(response.rightAnswerNb);
                $('.stats:nth(' + rowGamer + ') .panel-title span').html(response.score);
                $('.infoAnswer').html(response.validity);
                $('.stats .panel-collapse').removeClass('in');
                $('.stats:nth(' + rowGamer + ') .panel-collapse').addClass('in');

                if (rowGamer == len - 1) {
                    rowGamer = 0;
                } else {
                    rowGamer++;
                }
                randomGamer = $('.stats:nth(' + rowGamer + ') .panel-title em').text();
                $('#modal').delay(2000).fadeOut('fast');
                $('.messageInfo').html('<strong>' + randomGamer + '</strong>, <br/> c\'est maintenant à ton tour.');

                if (response.infoScore) {
                    $('.infoAnswer').append('<br/> Tu gagnes ' + response.infoScore + ' points.');
                    that.css('background-color', 'limegreen');
                } else {
                    that.css('background-color', '#f45c6e');
                }
                // dice.children().hide();            

            },
            error: function () {
                alert('erreur ajaxvalidanswer');
            }
        });
    }

});