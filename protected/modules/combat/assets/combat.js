var combat = {
  selector: {
    area_log: '.fight-log',
    btn_start: '.btn-go',
    btn_autofight:  '.btn-autofight',
    fighter1 : '.fighter-1',
    fighter2 : '.fighter-2',
    title : '.make-choice .lines-bg',
  },
  startTimer: 30,
  init: function(){
    combat.startTimer = 1*$(combat.timer.selector).data('move') || combat.startTimer;
    combat.timer.init(combat.startTimer);
    $(combat.selector.btn_autofight).on('click', function(){
      if (!$(this).is(':disabled')) {
        $(this).attr('disabled', 'disabled').appendTo('.combat-fighters').wrap('<p class="lines-nobg lines-bg"></p>');
        $(combat.selector.btn_start).parent().hide();
        $('.make-choice').hide();
        $.post('/combat/control/automove', function(){
          $(combat.selector.btn_start).click();
          combat.startTimer = 0;
        });
      }
    });
    $(combat.selector.btn_start).on('click', function(){
      if (!$(this).is(':disabled')){
        var enemy_turn_time = 35;
        if (combat.startTimer>0) {
          enemy_turn_time = combat.timer.to_end + 5;
        }
        combat.timerEnemy.init(enemy_turn_time);
        combat.timer.stop();
        combat.waitForEnemy();
        $.post('/combat/control/hit', {
          attack: $('.radio-attack:checked').val(),
          block:  $('.radio-def:checked').val()
        }, function(data){
          combat.drawLog(data);
          load_info();
        }, 'json');
      }
    });
    $('body').on({
      combatNextRound:    function(e, data){
        combat.timerEnemy.stop();
        combat.drawLog(data);
        combat.timer.init(combat.startTimer+data.delay);
        combat.waitForMe();
      },
      combatComplete:     function(e, data){
        $(combat.selector.btn_start).parent().remove();
        $(combat.selector.btn_autofight).remove();
        $(combat.selector.title).html(data.text);
        combat.timerEnemy.stop();
        combat.drawLog(data);
        combat.timer.stop();
      },
      combatMoveComplete: function(e, data){
        combat.timerEnemy.stop();
        combat.drawLog(data);
      },
    });
  },
  timer: {
    selector: 'span.timer',
    to_end: 0,
    h: null,
    init: function(time){
      combat.timer.stop();
      combat.timerEnemy.stop();
      var delta = 1*$(combat.timer.selector).data('time') || 0;
      $(combat.timer.selector).data('time', 0);

      combat.timer.to_end = time - delta;
      combat.timer.h = setInterval(function(){
        combat.timer.to_end--;
        if (combat.timer.to_end <= 0) {
          clearInterval(combat.timer.h);
          combat.timer.to_end = 0;
          $(combat.selector.btn_start).click();
        }
        combat.timer.draw();
      }, 1000);
      combat.timer.draw();
    },
    stop: function(){
      clearInterval(combat.timer.h);
      $(combat.timer.selector).html('');
    },
    draw: function(){
      text = 1*combat.timer.to_end;
      if (text < 10) text = '0' + text;
      $(combat.timer.selector).html(text);
    }
  },
  timerEnemy: {
    selector: 'span.enemy-timer',
    to_end: 0,
    h: null,
    init: function(time){
      combat.timerEnemy.stop();
      combat.timerEnemy.to_end = time;
      combat.timerEnemy.h = setInterval(function(){
        combat.timerEnemy.to_end--;
        if (combat.timerEnemy.to_end <= 0) {
          clearInterval(combat.timerEnemy.h);
          combat.timerEnemy.to_end = 0;
          combat.reportEnemyProblem();
        }
        combat.timerEnemy.draw();
      }, 1000);
      combat.timerEnemy.draw();
    },
    stop: function(){
      clearInterval(combat.timerEnemy.h);
      $(combat.timerEnemy.selector).html('');
    },
    draw: function(){
      text = 1*combat.timerEnemy.to_end;
      if (text < 10) text = '0' + text;
      $(combat.timerEnemy.selector).html(text);
    }
  },
  waitForEnemy: function(){
    $(combat.selector.btn_start).attr('disabled', 'disabled').html(combatLang.enemyTurn + ' <span class="enemy-timer"></span>');
  },
  waitForMe: function(){
    $(combat.selector.btn_start).removeAttr('disabled').html(combatLang.goGoGo);
  },
  drawLog: function(data){
    if (data.log) {
      var log = data.log;
      $(combat.selector.area_log).empty();
      for(i in log){
        var div = $('<div class="round">');
        for(n in log[i]){
          div.append($('<p>').html(log[i][n]));
        }
        $(combat.selector.area_log).prepend(div);
      }
    }

    if (data.hp) {
      for(var i in data.hp){
        data.hp[i] = parseInt(data.hp[i]);
        var el = $('.red-line', '.player-'+i);
        var max_hp = 1*$(el).data('hp');
        $(el).attr('title', data.hp[i] + ' / ' + max_hp);
        $(el).animate({
          width: Math.round(100 * data.hp[i] / max_hp) + '%'
        }, 500);
      }
    }
  },
  reportEnemyProblem: function(){
    $.post('/combat/control/problem', function(data){
      combat.drawLog(data);
    }, 'json');
  }
};

$(document).ready(function () {
  combat.init();
});
