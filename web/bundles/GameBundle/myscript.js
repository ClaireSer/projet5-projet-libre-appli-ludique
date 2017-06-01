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
    var randomNumber;
    var bonusDifficulty;
    var gamerId;

    var allGamersId = [];
    $('.stats').each(function (i) {
        allGamersId[i] = $(this).children().children().children('p:last').text();
    })
    
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

            var finalScore = parseInt($('.stats:nth(' + rowGamer + ') .panel-title span').text());
            finalScore += 30;
            $('.stats:nth(' + rowGamer + ') .panel-title span').html(finalScore);

            var finalScores = [];
            $('.stats').each(function (i) {
                finalScores[i] = $(this).children('.panel-heading').children().children().children('span').text();
            })

            var txtWin = 'Bravo ! <br/>Tu as fini la partie en premier. <br/>Tu bénéficies d\'un bonus de 30 points.<br/>';
            var scoreWinner = Math.max($('.stats:nth(0) .panel-title span').text(), $('.stats:nth(1) .panel-title span').text(), $('.stats:nth(2) .panel-title span').text());
            var winnerName = '';
            $('.stats').each(function () {
                if ($(this).children('.panel-heading').children().children().children('span').text() == scoreWinner) {
                    winnerName = $(this).children('.panel-heading').children().children().children('em').text();
                    winnerId = $(this).children('.panel-collapse').children().children('p:last').text()
                }
            })
            var winner = 'Le gagnant de la partie est <strong>' + winnerName + '</strong, avec un score de ' + scoreWinner + ' points.<br/>';
            $('.messageInfo').html(txtWin).append(winner).fadeIn();

            var exprStatsRegex = /(^\D+)\d+/;
            pathStatsCurrent = pathStats.replace(exprStatsRegex, '$1' + winnerId);

            $.ajax({
                url: pathStatsCurrent,
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({allGamersId, finalScores}),
                success: function (response) {
                    response.forEach(function(data, gamer) {
                        $('.stats:nth(' + gamer + ') p:nth(0) span').html(data[0].cumulScore);
                        $('.stats:nth(' + gamer + ') p:nth(1) span').html(data[0].bestScore);
                        $('.stats:nth(' + gamer + ') p:nth(2) span:nth(0)').html(data[0].gameWonNb);
                        $('.stats:nth(' + gamer + ') p:nth(2) span:nth(1)').html(data[0].gamePlayedNb);
                        $('.stats:nth(' + gamer + ') p:nth(4) span').html(data[0].level);
                    })
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
                    $(this).addClass(activeCase);
                    cumulDiceGamerArray[rowGamer] = cumulDiceGamer;
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
                
            });
            
            $('.board td').each(function () {
                var that = $(this);
                // pour chaque case du plateau, on retourne dans un tableau la valeur des dés cumulés (cumul) 
                // de la case sur laquelle le joueur a atterri (that.text()).
                var listOfCumulDiceGamers = cumulDiceGamerArray.filter(function (cumul) {
                    return that.text() == cumul && cumul != 0;
                });
                // on regarde dans ce tableau s'il y a au moins 2 valeurs identiques
                if (listOfCumulDiceGamers.length > 1) {
                    that.addClass('multipleGamers');
                }
            });
            
            $('#modal').delay(1000).fadeIn('slow');
        }
        return rowGamer;
        
    }


    function ajaxRandomQuestion(urlType, color) {
        $('.infoAnswer').html('');

        var exprUrl = /(^\D+\/\d+\/)\d+/;
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
                    var pathValidAnswerCurrent = pathValidAnswer.replace(expr, '$1' + $(this).attr('id') + '/' + gamerId + '/' + randomNumber + '/' + score + '/' + bonusDifficulty);
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
                dice.children().hide();            

            },
            error: function () {
                alert('erreur ajaxvalidanswer');
            }
        });
    }

});