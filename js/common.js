$(function() {
  // ハンバーガーメニュー制御
  $('.drawer-button').on('click', function() {
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
      $('.drawer-menu').addClass('active');
    } else {
      $('.drawer-menu').removeClass('active');
    }
  });

  // フィルタークリックでもメニューを閉じる
  $('#filter').on('click', function() {
    $('.drawer-button').removeClass('active');
    $('.drawer-menu').removeClass('active');
  });
  
  // トップに戻るボタンの表示切り替え
  function viewToggleBtn() {
    // トップに戻るボタンを表示するスクロール量(px)
    const mark = 300;
    // 現在のスクロール位置
    const current = $(window).scrollTop();
    if(current >= mark) {
        // 達していれば表示
        $('#tpBtn').fadeIn();
    }
    else {
        // 達していなければ非表示
        $('#tpBtn').fadeOut();
    }
  }

  // scrollイベント間引き用の時間変数
  let timeoutId;
  $(window).scroll(function() {
    if(timeoutId) {
      return;
    }
    timeoutId = setTimeout(function() {
      timeoutId = 0;
      viewToggleBtn();
    }, 500) ;
  });

  // Safariで必要なのでスムーズスクロール
  $(document).on('click','a[href^="#"]', function() {
    const adjust = 0;
    const speed = 100;
    let href= $(this).attr('href');
    let target = $(href == '#' || href == '' ? 'html' : href);
    let position = target.offset().top + adjust;
    $('body,html').animate({scrollTop:position}, speed, 'swing');
    return false;
  });

});