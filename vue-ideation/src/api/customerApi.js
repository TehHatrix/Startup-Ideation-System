import api from "./config";

export default {
  async createCustomer(interviewID,payload) {
   return api.post(`api/customer/store/${interviewID}`,payload);
  },

  async getAllCustomer(interviewID){
    return api.get(`api/customer/index/${interviewID}`);
  },

  async updateCustomer(custID,payload){
    return api.post(`api/customer/update/${custID}`,payload);
  },

  async updateScoreCustomer(custID,score){
    return api.post(`api/customer/update/${custID}/score`,score);
  },

  async updateLogsCustomer(custID,logs){
    return api.post(`api/customer/update/${custID}/logs`,logs);
  },

  async deleteCustomer(custID){
    return api.delete(`api/customer/delete/${custID}`);
  }


}

//   async getInterview(interviewId){
//     return api.get(`api/interview/index/${interviewId}`)
//   },

//   async updateScript(interviewId,text){
//     return api.post(`api/interview/updatescript/${interviewId}`,text)
//   }
