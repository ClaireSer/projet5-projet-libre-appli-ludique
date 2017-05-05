$(function () {
    var minNumber = 1;
    var maxNumber = 6;
    var dice = $('.dice');
    var cumulDiceGamer1 = 0;
    var firstTime = true;
    var showModal;
    var pathValidAnswerCurrent;
    var randomNumber;
    var bonusDifficulty;
    var finalScore;
    var bestScore;
    var gamerId;    

    dice.children().hide();

    // get a random gamer
    var len = $('.stats').length;
    var rowGamer = Math.floor( Math.random() * len );
    var randomGamer = $('.stats:nth('+ rowGamer +') > p:first').text();
    $('.messageInfo strong').append(randomGamer);

    $('.stats').each(function() {
        var that = $(this);
        if (that.children().first().text() == randomGamer) {
            gamerId = that.children().last().text();
        }
    })

    $('.buttonDice').on('click', function(e) {
        e.preventDefault();
        randomNumber = Math.floor(Math.random() * maxNumber) + minNumber;
        dice.children().hide();
        
        $('.dice .die' + randomNumber).show();
        cumulDiceGamer1 += randomNumber;
        if (cumulDiceGamer1 > 64) {
            cumulDiceGamer1 -= randomNumber;
            $('.rightOrWrongAnswer').html('Passez votre tour !').fadeIn();
            return false;
            
        } else if (cumulDiceGamer1 == 64) {
            $('.board .win').addClass('activeCase');
            $('.rightOrWrongAnswer').html('Bravo !').fadeIn();
            finalScore = parseInt($('.stats p:nth(1) span').text());
            bestScore = parseInt($('.stats p:nth(2) span').text());
            if (finalScore > bestScore) {
                var exprBestScore = /(^\D+)\d+\/\d+/;
                pathBestScoreCurrent = pathBestScore.replace(exprBestScore, '$1' + finalScore + '/' + gamerId);

                $.ajax({
                    url: pathBestScoreCurrent,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        $('.stats p:nth(2) span').html(response);
                    },
                    error: function() {
                        alert('erreur score');
                    }
                });
            }
            return false;
        } else {
            $('.board td').each(function() {
                $(this).removeClass('activeCase');
                if ($(this).text() == cumulDiceGamer1) {
                    $(this).addClass('activeCase');
                    $('.questions').show();

                    if ($(this).text() % 4 == 0) {
                        ajaxResponse(url0, 'red');

                    } else if ($(this).text() % 4 == 1) {
                        ajaxResponse(url1, 'blue');

                    } else if ($(this).text() % 4 == 2) {
                        ajaxResponse(url2, 'yellow');
                        
                    } else if ($(this).text() % 4 == 3) {
                        ajaxResponse(url3, 'green');
                    }
                }
            })
        }
        
    
        // end of game
        // if (cumulDiceGamer1 == 64) {
        //     $('.board .win').addClass('activeCase');
        //     alert('gagné !');
        //     finalScore = parseInt($('.stats p:nth(1) span').text());
        //     bestScore = parseInt($('.stats p:nth(2) span').text());
        //     if (finalScore > bestScore) {
        //         var exprBestScore = /(^\D+)\d+\/\d+/;
        //         pathBestScoreCurrent = pathBestScore.replace(exprBestScore, '$1' + finalScore + '/' + gamerId);

        //         $.ajax({
        //             url: pathBestScoreCurrent,
        //             type: 'POST',
        //             dataType: 'json',
        //             success: function(response) {
        //                 alert('ok');
        //                 $('.stats p:nth(2) span').html(response);
        //             },
        //             error: function() {
        //                 alert('erreur score');
        //             }
        //         });
        //     }
        //     return false;                    
        // }

        showModal = setTimeout(function() {
            $('#modal').fadeIn('slow');
        }, 1000)
        
    })


    function ajaxResponse (urlType, color) {

        $.ajax({
            url: urlType,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                            
                if (!firstTime) {
                    var classStr = $('.questions').attr('class');
                    var lastClass = classStr.substr( classStr.lastIndexOf(' ') + 1);
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
                response.answers.forEach(function(data) {
                    $('.questions .answers').append('<div id="'+ data.id +'" class="answer answer'+ index +'">'+ data.answer +'</div>');
                    index++;
                })
                
                var parent = $('.questions .answers');
                var divsAnswer = parent.children();
                while(divsAnswer.length) {
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
                
                 $('div.answer').on('click', function() {
                    var expr = /(^\D+)\d+\/\d+\/\d+\/\d+\/\d+/;
                    var score = parseInt($('.stats p:nth(1) span').text());
                    pathValidAnswerCurrent = pathValidAnswer.replace(expr, '$1' + $(this).attr('id') + '/' + gamerId + '/' + randomNumber + '/' + score + '/' + bonusDifficulty);
                    ajaxValidAnswer(pathValidAnswerCurrent);
                })
                                            
            },
            error: function() {
                alert('erreur');
            }
        });
    }

    function ajaxValidAnswer(path) {
        $.ajax({
            url: path,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                $('#modal').fadeOut('fast');
                clearTimeout(showModal);
                $('.rightOrWrongAnswer').html(response.validity).fadeIn().delay(2500).fadeOut();
                $('.stats p:nth(4) span').html(response.rightAnswerNb);
                $('.stats p:nth(1) span').html(response.score);
            },
            error: function() {
                alert('erreur ajaxvalidanswer');
            }
        });
    }
     
});