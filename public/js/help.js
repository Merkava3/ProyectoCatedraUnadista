export default class Help {
    static organizeData(data) {
        if (Array.isArray(data)) {
            if (data.length === 1) {
                return data[0];
            }
            return data.reduce((acc, item, index) => {
                acc[index] = item;
                return acc;
            }, {});
        }
        return data;
    }
}