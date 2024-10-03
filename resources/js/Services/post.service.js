import sysService from "./sys.service";

const API_URL = "/api/post";

class PostService {
    async getPage(params) {
        return sysService.serviceAuth("GET", API_URL + "/page" + params);
    }
    async destroy(id) {
        return sysService.serviceAuth("DELETE", API_URL + "/destroy/" + id);
    }
    async store(data) {
        return sysService.serviceAuth("POST", API_URL + "/store", data);
    }
}

export default new PostService();