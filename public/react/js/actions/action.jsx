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

export const funcSelectModalNotification = (show, unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage) => ({
  type: 'MODAL_NOTIFICATION',
  show,
  unreadTotal,
  unreadArr,
  alreadyReadTotal,
  alreadyReadArr,
  activePage
});


// --------------------------------------------------
//   通知
// --------------------------------------------------

export const funcSelectNotificationUnreadCount = value => ({
  type: 'NOTIFICATION_UNREAD_COUNT',
  value
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
