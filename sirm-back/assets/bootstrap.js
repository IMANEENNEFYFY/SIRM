import { startStimulusApp } from '@symfony/stimulus-bundle';
import ExamenController from './controllers/examen_controller.js';

const app = startStimulusApp();
app.register('examen', ExamenController);
