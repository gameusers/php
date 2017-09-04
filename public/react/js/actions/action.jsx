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
//   通知
// --------------------------------------------------

export const funcNotificationUnreadCount = value => ({
  type: 'NOTIFICATION_UNREAD_COUNT',
  value
});


// --------------------------------------------------
//   フッター
// --------------------------------------------------

export const funcFooterCardType = (cardType, gameCommunityRenewalArr, gameCommunityAccessArr, userCommunityAccessArr) => ({
  type: 'FOOTER_CARD_TYPE',
  cardType,
  gameCommunityRenewalArr,
  gameCommunityAccessArr,
  userCommunityAccessArr
});


// --------------------------------------------------
//   モーダル
// --------------------------------------------------

export const funcModalNotificationShow = (show, unreadTotal, unreadArr, alreadyReadTotal, alreadyReadArr, activePage) => ({
  type: 'MODAL_NOTIFICATION_SHOW',
  show,
  unreadTotal,
  unreadArr,
  alreadyReadTotal,
  alreadyReadArr,
  activePage
});
