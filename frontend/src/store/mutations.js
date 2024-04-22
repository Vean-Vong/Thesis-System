export default {
  PUSH_NOTIFICATION(state, notification) {
    state.notifications.push({
      ...notification,
      id: (Math.random().toString() + Date.now().toString(36)).substring(2),
    });
  },

  REMOVE_NOTIFICATION(state, notification) {
    state.notifications = state.notifications.filter(
      (x) => x.id !== notification.id
    );
  },
};
