import axios from "axios";

class SysService {
    async serviceAuth(
        methods = "GET",
        url = "",
        data = {},
        multipart = false,
        customHeaders = [],
        responseType = "json"
    ) {
        customHeaders.push({
            key: "Content-Type",
            value: multipart ? "multipart/form-data" : "application/json",
        });
        return axios({
            url: url,
            method: methods,
            data: data,
            headers: {
                ...Object.fromEntries(
                    customHeaders.map((header) => [
                        header["key"],
                        header["value"],
                    ])
                ),
            },
            responseType: responseType,
        })
            .then((response) => {
                return response.data;
            })
            .catch((error) => {
                return error.response.data;
            });
    }

    async serviceGuest(methods = "GET", url = "", data = {}) {
        return axios({
            url: url,
            method: methods,
            data: data,
        })
            .then((response) => {
                return response.data;
            })
            .catch((error) => {
                return error.response.data;
            });
    }

    async fileTypeIcons() {}
    async fileTypeColor() {}
}

export default new SysService();