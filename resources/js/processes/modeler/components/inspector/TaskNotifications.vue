<template>
    <div>
        <div class="form-group">
            <label>Task Notifications</label>
            <div class="notification-settings-group">
                <div class="notification-settings-header">Requester</div>
                <div class="custom-control custom-switch">
                    <input v-model="requesterAssigned" type="checkbox" class="custom-control-input" id="notify-requester-assigned">
                    <label class="custom-control-label" for="notify-requester-assigned">Assigned</label>
                </div>
                <div class="custom-control custom-switch">
                    <input v-model="requesterCompleted"  type="checkbox" class="custom-control-input" id="notify-requester-completed">
                    <label class="custom-control-label" for="notify-requester-completed">Completed</label>
                </div>
            </div>
            <div class="notification-settings-group">
                <div class="notification-settings-header">Assignee</div>
                <div class="custom-control custom-switch">
                    <input v-model="assigneeAssigned"  type="checkbox" class="custom-control-input" id="notify-assignee-assigned">
                    <label class="custom-control-label" for="notify-assignee-assigned">Assigned</label>
                </div>
                <div class="custom-control custom-switch">
                    <input v-model="assigneeCompleted"  type="checkbox" class="custom-control-input" id="notify-assignee-completed">
                    <label class="custom-control-label" for="notify-assignee-completed">Completed</label>
                </div>
            </div>
            <div class="notification-settings-group">
                <div class="notification-settings-header">Participants</div>
                <div class="custom-control custom-switch">
                    <input v-model="participantsAssigned"  type="checkbox" class="custom-control-input" id="notify-participants-assigned">
                    <label class="custom-control-label" for="notify-participants-assigned">Assigned</label>
                </div>
                <div class="custom-control custom-switch">
                    <input v-model="participantsCompleted"  type="checkbox" class="custom-control-input" id="notify-participants-completed">
                    <label class="custom-control-label" for="notify-participants-completed">Completed</label>
                </div>
            </div>
            
            <!-- <table id="table-notifications" class="table">
                <thead>
                    <tr>
                        <th class="notify"></th>
                        <th class="action">Assigned</th>
                        <th class="action">Completed</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="notify">Requester</td>
                        <td class="action">
                            <div class="custom-control custom-switch">
                                <input v-model="notifications.requester.assigned" type="checkbox" class="custom-control-input" id="notify-requester-assigned">
                                <label class="custom-control-label" for="notify-requester-assigned"></label>
                            </div>
                        </td>
                        <td class="action">
                            <div class="custom-control custom-switch">
                                <input v-model="notifications.requester.completed"  type="checkbox" class="custom-control-input" id="notify-requester-completed">
                                <label class="custom-control-label" for="notify-requester-completed"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="notify">Assignee</td>
                        <td class="action">
                            <div class="custom-control custom-switch">
                                <input v-model="notifications.assignee.assigned"  type="checkbox" class="custom-control-input" id="notify-assignee-assigned">
                                <label class="custom-control-label" for="notify-assignee-assigned"></label>
                            </div>
                        </td>
                        <td class="action">
                            <div class="custom-control custom-switch">
                                <input v-model="notifications.assignee.completed"  type="checkbox" class="custom-control-input" id="notify-assignee-completed">
                                <label class="custom-control-label" for="notify-assignee-completed"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="notify">Participants</td>
                        <td class="action">
                            <div class="custom-control custom-switch">
                                <input v-model="notifications.participants.assigned"  type="checkbox" class="custom-control-input" id="notify-participants-assigned">
                                <label class="custom-control-label" for="notify-participants-assigned"></label>
                            </div>
                        </td>
                        <td class="action">
                            <div class="custom-control custom-switch">
                                <input v-model="notifications.participants.completed"  type="checkbox" class="custom-control-input" id="notify-participants-completed">
                                <label class="custom-control-label" for="notify-participants-completed"></label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table> -->
        </div>
    </div>
</template>

<script>

    function notificationTemplate() {
      this.requester = {
        assigned: false,
        completed: false,
      };
      this.assignee = {
        assigned: false,
        completed: false,
      };
      this.participants = {
        assigned: false,
        completed: false,
      };
    };

    export default {
        props: ["value", "label", "helper", "property"],
        data() {
            return {
              content: "",
              users: [],
              requesterAssigned: false,
              requesterCompleted: false,
              assigneeAssigned: false,
              assigneeCompleted: false,
              participantsAssigned: false,
              participantsCompleted: false,              
            };
        },
        watch: {
          requesterAssigned: function(value) {
            this.notifications.requester.assigned = value;
          },
          requesterCompleted: function(value) {
            this.notifications.requester.completed = value;
          },
          assigneeAssigned: function(value) {
            this.notifications.assignee.assigned = value;
          },
          assigneeCompleted: function(value) {
            this.notifications.assignee.completed = value;
          },
          participantsAssigned: function(value) {
            this.notifications.participants.assigned = value;
          },
          participantsCompleted: function(value) {
            this.notifications.participants.completed = value;
          },
          modelerId: function(newValue, oldValue) {
            this.loadNotifications();
          }
        },
        methods: {
          loadNotifications() {
            this.requesterAssigned = this.notifications.requester.assigned;
            this.requesterCompleted = this.notifications.requester.completed;
            this.assigneeAssigned = this.notifications.assignee.assigned;
            this.assigneeCompleted = this.notifications.assignee.completed;
            this.participantsAssigned = this.notifications.participants.assigned;
            this.participantsCompleted = this.notifications.participants.completed;            
          }
        },
        computed: {
          process() {
            return this.$parent.$parent.$parent.$parent.process;
          },
          modelerId() {
            return this.$parent.$parent.highlightedNode._modelerId;
          },
          node() {
            return this.$parent.$parent.highlightedNode;
          },
          nodeId() {
            return this.node.definition.id;
          },
          notifications() {
            if (this.node.notifications === undefined) {
              if (this.process.task_notifications[this.nodeId] === undefined) {
                this.node.notifications = new notificationTemplate();
              } else {
                this.node.notifications = this.process.task_notifications[this.nodeId];
              }
            }
            return this.node.notifications;
          }
        },
        mounted() {
          this.loadNotifications();
        }
    };
</script>

<style lang="scss" scoped>
    .notification-settings-group {
      margin-bottom: 10px;
    }

    .notification-settings-header {
      font-weight: bold;
    }
    
    .custom-control-label {
      margin-bottom: 0;
      padding-top: 3px;
    }
</style>
