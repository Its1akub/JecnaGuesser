import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "btn"]

    select(event) {
        this.btnTargets.forEach(b => b.classList.remove("diff-btn-selected"));

        const clickedButton = event.currentTarget;
        clickedButton.classList.add("diff-btn-selected");

        this.inputTarget.value = clickedButton.dataset.value;
    }
}
