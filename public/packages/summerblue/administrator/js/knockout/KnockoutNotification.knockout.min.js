/**
 * Knockout Notification plugin v1.1.0
 * (c) 2011 Jan Hartigan - http://www.janhartigan.com
 * License: dual (MIT, GPL)
 */
ko.bindingHandlers.notification={update:function(e,t,n,r){var i=t(),s=typeof i=="object"?i:{message:i},o=ko.utils.unwrapObservable(s.message),u=s.duration!==undefined?ko.utils.unwrapObservable(s.duration):5e3,a=s.fadeoutDuration!==undefined?ko.utils.unwrapObservable(s.fadeoutDuration):200,f=s.hide!==undefined?ko.utils.unwrapObservable(s.hide):true,l=s.fade!==undefined?ko.utils.unwrapObservable(s.fade):true,c=s.callback!==undefined?ko.utils.unwrapObservable(s.callback):function(){},h=typeof jQuery!="undefined";if(o===null||o===undefined)o="";e.innerHTML=o;clearTimeout(e.notificationTimer);if(o==""){e.style.display="none";return}if(h)jQuery(e).stop(true,true).show();else e.style.display="";if(f){e.notificationTimer=setTimeout(function(){if(h){if(l)jQuery(e).fadeOut(a,function(){s.message("");c()});else{jQuery(e).hide();s.message("");c()}}else{e.style.display="none";c()}},u)}else{c()}}};
