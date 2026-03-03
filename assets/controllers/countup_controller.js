// assets/controllers/countup_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['display'];

    connect() {
        this.totalMs = 0;
        this.running = false;
        this.timer = null;
        this.resume();
    }

    disconnect() {
        this.stopTimer();
    }

    pause() {
        if (!this.running) return;
        this.stopTimer();
        this.accumulateTime();
        this.draw();
    }

    resume() {
        if (this.running) return;

        this.stopTimer();

        this.running = true;
        this.lastTimestamp = Date.now();

        this.timer = setInterval(() => {
            this.accumulateTime();
            this.draw();
        }, 200);
    }

    accumulateTime() {
        const now = Date.now();
        this.totalMs += (now - this.lastTimestamp);
        this.lastTimestamp = now;
    }

    draw() {
        const seconds = Math.floor(this.totalMs / 1000);
        const newText = `${seconds}s`;
        if (this.displayTarget.innerHTML !== newText) {
            this.displayTarget.innerHTML = newText;
        }
    }

    stopTimer() {
        this.running = false;
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    }
}
