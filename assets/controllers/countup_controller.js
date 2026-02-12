import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['display'];
    static values = { start: String };

    connect() {
        this.startTime = new Date().getTime();
        this.update();
        this.timer = setInterval(() => this.update(), 1000);
    }

    disconnect() {
        clearInterval(this.timer);
    }

    update() {
        const now = new Date().getTime();
        this.displayTarget.innerHTML = `${ Math.floor((now - this.startTime) / 1000)}s`;
    }
}
