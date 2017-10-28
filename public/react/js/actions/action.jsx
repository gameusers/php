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

export const funcModalMapNotificationShow = value => ({
  type: 'MODAL_MAP_NOTIFICATION_SHOW',
  value
});



// --------------------------------------------------
//   通知
// --------------------------------------------------

export const funcNotificationMapUnreadCount = value => ({
  type: 'NOTIFICATION_MAP_UNREAD_COUNT',
  value
});


export const funcNotificationMapResetActivePage = () => ({
  type: 'NOTIFICATION_MAP_RESET_ACTIVE_PAGE'
});


export const funcNotificationMap = (unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage) => ({
  type: 'NOTIFICATION_MAP',
  unreadTotal,
  unreadArr,
  alreadyReadTotal,
  alreadyReadArr,
  activePage
});



// --------------------------------------------------
//   ドロワーメニュー / モバイル用
// --------------------------------------------------

export const funcMenuDrawerActive = () => ({
  type: 'MENU_DRAWER_ACTIVE'
});



// --------------------------------------------------
//   フッター
// --------------------------------------------------

export const funcSelectFooterCardType = (cardType, gameCommunityRenewalList, gameCommunityAccessList, userCommunityAccessList) => ({
  type: 'FOOTER_CARD_TYPE',
  cardType,
  gameCommunityRenewalList,
  gameCommunityAccessList,
  userCommunityAccessList
});
