import { getConfig, modal, registerMenuEntry, url } from '@humhub/vue';

/**
 * Adds the "Report" entry to the `⋮` menu of every comment (menu id `comment.controls`).
 *
 * The label and the permission to report come from the server: `ReportContentVueAsset` is registered
 * together with the wall entry controls, which also passes the `reportcontent` JS config.
 * The report form itself is still the server-rendered modal of `ReportController::actionIndex()`.
 */
registerMenuEntry('comment.controls', {
    id: 'report',
    label: () => getConfig('reportcontent').text.report,
    icon: 'flag',
    sortOrder: 1000,
    condition: ({ comment }) => {
        const author = comment.author || comment.createdBy;
        const user = getConfig('user');

        return user.isGuest !== true && !!author && author.guid !== user.guid;
    },
    onClick: ({ comment }) => {
        modal.load(url('reportcontent/report', { contentId: comment.contentId, commentId: comment.id }));
    },
});
