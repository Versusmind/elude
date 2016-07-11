class Lol {
    constructor(options) {
        this.model = options.model;
        this.template = options.template;
    }

    render() {
        return _.template(this.template, this.model.toObject());
    }
}