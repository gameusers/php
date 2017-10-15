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



// --------------------------------------------------
//   コンテンツ / アプリ / 購入
// --------------------------------------------------

// export const funcContentsAppPayFormShareButtonsWebSiteName = value => ({
//   type: 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_WEB_SITE_NAME',
//   value
// });
//
// export const funcContentsAppPayFormShareButtonsWebSiteUrl = value => ({
//   type: 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_WEB_SITE_URL',
//   value
// });
//
// export const funcContentsAppPayFormShareButtonsAgreement = value => ({
//   type: 'CONTENTS_APP_PAY_FORM_SHARE_BUTTONS_AGREEMENT',
//   value
// });
