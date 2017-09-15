// --------------------------------------------------
//   共通
// --------------------------------------------------

export const funcUrlDirectory = (urlDirectory1, urlDirectory2, urlDirectory3) => ({
  type: 'URL_DIRECTORY',
  urlDirectory1,
  urlDirectory2,
  urlDirectory3
});



// --------------------------------------------------
//   モーダル
// --------------------------------------------------

export const funcModalObjNotificationShow = value => ({
  type: 'MODAL_OBJ_NOTIFICATION_SHOW',
  value
});



// --------------------------------------------------
//   通知
// --------------------------------------------------

export const funcNotificationObjUnreadCount = value => ({
  type: 'NOTIFICATION_OBJ_UNREAD_COUNT',
  value
});


export const funcNotificationObjResetActivePage = () => ({
  type: 'NOTIFICATION_OBJ_RESET_ACTIVE_PAGE'
});


export const funcNotificationObj = (unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage) => ({
  type: 'NOTIFICATION_OBJ',
  unreadTotal,
  unreadArr,
  alreadyReadTotal,
  alreadyReadArr,
  activePage
});


// export const funcSelectNotificationUnreadCount = value => ({
//   type: 'NOTIFICATION_UNREAD_COUNT',
//   value
// });



// --------------------------------------------------
//   ドロワーメニュー / スマートフォン・タブレット用
// --------------------------------------------------

export const funcMenuDrawerActive = () => ({
  type: 'MENU_DRAWER_ACTIVE'
});



// --------------------------------------------------
//   フッター
// --------------------------------------------------

export const funcSelectFooterCardType = (cardType, gameCommunityRenewalArr, gameCommunityAccessArr, userCommunityAccessArr) => ({
  type: 'FOOTER_CARD_TYPE',
  cardType,
  gameCommunityRenewalArr,
  gameCommunityAccessArr,
  userCommunityAccessArr
});
