<template>
  <div id="modeler-app">
    <div class="navbar">
      <div>{{process.name}}</div>
      <div class="actions">
          <font-awesome-icon @click="saveBpmn" icon="save">Save</font-awesome-icon>
      </div>
    </div>
    <div class="modeler-container">
      <modeler ref="modeler" @validate="validationErrors = $event" />
    </div>
    <statusbar>
      <template slot="secondary">
        Last Saved: {{lastSaved}}
      </template>

      <validation-status :validation-errors="validationErrors"/>
    </statusbar>
  </div>
</template>


<script>
import { Modeler, Statusbar, ValidationStatus } from "@processmaker/modeler";
import { library } from "@fortawesome/fontawesome-svg-core";

import {
  faCheckCircle,
  faTimesCircle,
  faSave,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import moment from 'moment';

library.add(faSave)

export default {
  name: "ModelerApp",
  components: {
    Modeler,
    Statusbar,
    FontAwesomeIcon,
    ValidationStatus
  },
  data() {
    return {
      process: window.ProcessMaker.modeler.process,
      validationErrors: {},
    };
  },
  computed: {
    lastSaved() {
      return moment(this.process.updated_at).fromNow();
    }
  },
  mounted() {
    ProcessMaker.$modeler = this.$refs.modeler;
  },
  methods: {
    getTaskNotifications() {
      var notifications = {};
      this.$refs.modeler.nodes.forEach(function(node) {
        let id = node.definition.id;
        if (node.notifications !== undefined) {
          notifications[id] = node.notifications;
        }
      });
      return notifications;
    },
    saveBpmn() {
      this.$refs.modeler.toXML((err, xml) => {
        if(err) {
          ProcessMaker.alert("There was an error saving: " + err, 'danger');
        } else {
          // lets save
          // Call process update
          var data = {
            name: this.process.name,
            description: this.process.description,
            task_notifications: this.getTaskNotifications(),
            bpmn: xml
          };
          ProcessMaker.apiClient.put('/processes/' + this.process.id, data)
          .then((response) => {
            this.process.updated_at = response.data.updated_at;
            // Now show alert
            ProcessMaker.alert('The process was saved.', 'success');
          })
          .catch((err) => {
            const message = err.response.data.message;
            const errors = err.response.data.errors;
            ProcessMaker.alert(message, 'danger');
            console.log(errors);
          })
        }

      });
    }

   }
};
</script>


<style lang="scss" scoped>
#modeler-app {
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  display: flex;
  flex-direction: column;
  position: absolute;
  width: 100%;
  max-width: 100%;
  height: 100%;
  max-height: 100%;
  .modeler-container {
    flex-grow: 1;
    overflow: hidden;
  }
  .navbar {
    font-weight: bold;
    height: 42px;
    min-height: 42px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 1.2em;
    background-color: #b6bfc6;

    color: white;
    border-bottom: 1px solid grey;
    padding-right: 16px;
    padding-left: 16px;
    .actions {
      button {
        border-radius: 4px;
        display: inline-block;
        padding-top: 4px;
        padding-bottom: 4px;
        padding-left: 8px;
        padding-right: 8px;
        background-color: grey;
        color: white;
        border-width: 1px;
        border-color: darkgrey;
        margin-right: 8px;
        font-weight: bold;
      }
    }
  }
}
</style>
