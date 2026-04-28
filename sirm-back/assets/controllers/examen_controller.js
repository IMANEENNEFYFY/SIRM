import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['errorBox', 'examensList', 'form', 'patientSelect', 'dateInput', 'typeSelect', 'machineSelect', 'descriptionInput'];
    static values = { page: Number };

    connect() {
        console.log('✅ ExamenController connected');
        const restoredFromCache = this.restoreCachedExamens();
        const hasInitialContent = this.hasRenderedExamens() || restoredFromCache;
        if (this.hasRenderedExamens()) {
            this.cacheRenderedExamens();
        }
        this.pageValue = 1;
        window.examenController = this;
        this.clearError();

        // Keep server-rendered data as source of truth on page (re)entry.
        if (!hasInitialContent) {
            this.chargerExamens({
                preserveOnEmpty: false,
                silentOnError: true,
            });
        }
    }

    disconnect() {
        if (window.examenController === this) {
            delete window.examenController;
        }
    }

    async chargerExamens({ preserveOnEmpty = false, silentOnError = false } = {}) {
        try {
            const response = await fetch(`/api/examens?page=${this.pageValue}`);
            const raw = await response.text();
            let payload = null;
            try {
                payload = raw ? JSON.parse(raw) : null;
            } catch (e) {
                payload = null;
            }

            if (!response.ok) {
                throw new Error(payload?.error || `Erreur HTTP ${response.status}`);
            }

            const examens = Array.isArray(payload)
                ? payload
                : Array.isArray(payload?.data)
                    ? payload.data
                    : [];

            if (preserveOnEmpty && examens.length === 0 && this.hasRenderedExamens()) {
                this.clearError();
                return false;
            }

            this.clearError();
            this.afficherExamens(examens);
            return true;
        } catch (error) {
            console.error('❌ Erreur:', error);
            if (!silentOnError && !this.hasRenderedExamens()) {
                this.showError(`Erreur de synchronisation API: ${error.message}`);
            }
            if (!this.examensListTarget.innerHTML.trim()) {
                const restoredFromCache = this.restoreCachedExamens();
                if (!restoredFromCache) {
                    this.examensListTarget.innerHTML = '<p class="text-center text-muted">Aucun examen en base de données</p>';
                }
            }
            return false;
        }
    }

    async creerExamen(event) {
        event.preventDefault();

        const data = {
            patientId: parseInt(this.patientSelectTarget.value),
            machineId: parseInt(this.machineSelectTarget.value),
            type: this.typeSelectTarget.value,
            date: this.dateInputTarget.value,
            description: this.descriptionInputTarget.value || null,
        };

        try {
            const response = await fetch('/api/examens', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Erreur lors de la création');
            }

            const examen = await response.json();
            this.formTarget.reset();
            alert('✅ Examen créé avec succès!');
            this.chargerExamens({ preserveOnEmpty: false });
        } catch (error) {
            alert('❌ ' + error.message);
        }
    }

    afficherExamens(examens) {
        if (!Array.isArray(examens) || examens.length === 0) {
            const restoredFromCache = this.restoreCachedExamens();
            if (restoredFromCache || this.hasRenderedExamens()) {
                return;
            }
            this.examensListTarget.innerHTML = '<p class="text-center text-muted">Aucun examen en base de données</p>';
            return;
        }

        const html = examens.map(e => `
            <div class="card mb-3 examen-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Patient: ${e?.patient?.prenom || ''} ${e?.patient?.nom || 'Inconnu'}</h5>
                            <p class="mb-1"><strong>Type:</strong> ${e?.type || '-'}</p>
                            <p class="mb-1"><strong>Date:</strong> ${this.formatDate(e?.date)}</p>
                            <p class="mb-1"><strong>Machine:</strong> ${e?.machine?.nom || 'Inconnue'} (${e?.machine?.modalite || '-'})</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge status-badge ${this.getStatusBadge(e?.statut)}">${e?.statut || 'INCONNU'}</span>
                                    ${e?.description ? `<p class="mt-2 small text-muted">${e.description}</p>` : ''}
                                </div>
                                <div>
                                    ${this.getActionButtons(e?.id, e?.statut)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        this.examensListTarget.innerHTML = html;
        this.cacheRenderedExamens();
        this.attachEventListeners();
    }

    formatDate(dateValue) {
        if (!dateValue) {
            return '-';
        }

        const parsedDate = new Date(dateValue);
        if (Number.isNaN(parsedDate.getTime())) {
            return dateValue;
        }

        return parsedDate.toLocaleString('fr-FR');
    }

    hasRenderedExamens() {
        return !!this.examensListTarget.querySelector('.examen-card');
    }

    cacheRenderedExamens() {
        if (!this.hasRenderedExamens()) {
            return;
        }

        try {
            const html = this.examensListTarget.innerHTML;
            sessionStorage.setItem('sirm:examens:list:html:v1', html);
            localStorage.setItem('sirm:examens:list:html:v1', html);
        } catch (error) {
            console.warn('⚠️ Impossible de sauvegarder le cache des examens', error);
        }
    }

    restoreCachedExamens() {
        try {
            const cachedHtml = sessionStorage.getItem('sirm:examens:list:html:v1')
                || localStorage.getItem('sirm:examens:list:html:v1');
            if (!cachedHtml || this.hasRenderedExamens()) {
                return false;
            }

            this.examensListTarget.innerHTML = cachedHtml;
            return this.hasRenderedExamens();
        } catch (error) {
            console.warn('⚠️ Impossible de restaurer le cache des examens', error);
            return false;
        }
    }

    showError(message) {
        if (this.hasErrorBoxTarget) {
            this.errorBoxTarget.innerHTML = `<div class="alert alert-warning">${message}</div>`;
        }
    }

    clearError() {
        if (this.hasErrorBoxTarget) {
            this.errorBoxTarget.innerHTML = '';
        }
    }

    attachEventListeners() {
        // Method to attach event listeners if needed
    }

    filtrerMachines() {
        const type = this.typeSelectTarget.value;
        const options = Array.from(this.machineSelectTarget.options);

        options.forEach(option => {
            const modalite = option.dataset.modalite;
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const visible = !type || modalite === type;
            option.hidden = !visible;
            if (!visible && option.selected) {
                option.selected = false;
            }
        });

        if (type) {
            const firstVisible = options.find(option => option.value && !option.hidden);
            if (firstVisible) {
                this.machineSelectTarget.value = firstVisible.value;
            }
        }
    }

    getStatusBadge(statut) {
        const badges = {
            'PLANIFIE': 'bg-info',
            'EN_COURS': 'bg-warning',
            'RECU': 'bg-success',
            'ANNULE': 'bg-danger',
        };
        return badges[statut] || 'bg-secondary';
    }

    getActionButtons(examenId, statut) {
        if (!examenId) {
            return '';
        }

        let actions = `<button class="btn btn-sm btn-outline-primary" onclick="window.examenController.changerStatut(${examenId}, 'EN_COURS')" ${statut !== 'PLANIFIE' ? 'disabled' : ''}>Commencer</button>`;

        if (statut === 'EN_COURS') {
            actions += ` <button class="btn btn-sm btn-outline-success" onclick="window.examenController.changerStatut(${examenId}, 'RECU')">Reçu</button>`;
        }

        if (statut !== 'RECU' && statut !== 'ANNULE') {
            actions += ` <button class="btn btn-sm btn-outline-danger" onclick="window.examenController.annulerExamen(${examenId})">Annuler</button>`;
        }

        return actions;
    }

    async changerStatut(examenId, nouveauStatut) {
        if (!confirm(`Changer le statut à ${nouveauStatut}?`)) return;

        try {
            const response = await fetch(`/api/examens/${examenId}/statut`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ statut: nouveauStatut }),
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error);
            }

            alert('✅ Statut mis à jour!');
            this.chargerExamens({ preserveOnEmpty: false });
        } catch (error) {
            alert('❌ ' + error.message);
        }
    }

    async annulerExamen(examenId) {
        if (!confirm('Êtes-vous sûr d\'annuler cet examen?')) return;

        try {
            const response = await fetch(`/api/examens/${examenId}`, {
                method: 'DELETE',
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error);
            }

            alert('✅ Examen annulé!');
            this.chargerExamens({ preserveOnEmpty: false });
        } catch (error) {
            alert('❌ ' + error.message);
        }
    }

    async pageSuivante() {
        this.pageValue++;
        const updated = await this.chargerExamens({ preserveOnEmpty: true, silentOnError: true });
        if (!updated) {
            this.pageValue--;
        }
    }

    async pagePrecedente() {
        if (this.pageValue > 1) {
            this.pageValue--;
            const updated = await this.chargerExamens({ preserveOnEmpty: true, silentOnError: true });
            if (!updated) {
                this.pageValue++;
            }
        }
    }
}
