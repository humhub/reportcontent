/*!
 * AUTO-GENERATED FILE — do not edit.
 * Compiled from reportcontent/vue/ via `grunt build-vue --module=reportcontent`.
 * See docs/develop/ui-js-vuejs.md
 */
(function(vue) {
  "use strict";
  vue.registerMenuEntry("comment.controls", {
    id: "report",
    label: () => vue.getConfig("reportcontent").text.report,
    icon: "flag",
    sortOrder: 1e3,
    condition: ({ comment }) => {
      const author = comment.author || comment.createdBy;
      const user = vue.getConfig("user");
      return user.isGuest !== true && !!author && author.guid !== user.guid;
    },
    onClick: ({ comment }) => {
      vue.modal.load(vue.url("reportcontent/report", { contentId: comment.contentId, commentId: comment.id }));
    }
  });
})(humhub.modules.vue);
//# sourceMappingURL=humhub.reportcontent.vue.js.map
