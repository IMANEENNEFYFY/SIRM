import { CommonModule } from '@angular/common';
import { Component, ElementRef, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import * as cornerstone from 'cornerstone-core';
import * as cornerstoneWADOImageLoader from 'cornerstone-wado-image-loader';
import * as dicomParser from 'dicom-parser';
import { DicomSource } from '../../core/models/sirm.models';
import { SirmApiService } from '../../core/services/sirm-api.service';

@Component({
  selector: 'app-viewer',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './viewer.component.html',
  styleUrl: './viewer.component.css'
})
export class ViewerComponent implements OnInit, OnDestroy {
  @ViewChild('dicomViewport', { static: true })
  dicomViewportRef!: ElementRef<HTMLDivElement>;

  erreur = '';
  sourceDicom: DicomSource | null = null;
  chargement = true;

  private cornerstoneReady = false;

  constructor(
    private readonly route: ActivatedRoute,
    private readonly api: SirmApiService
  ) {}

  ngOnInit(): void {
    const examenId = Number(this.route.snapshot.queryParamMap.get('examenId'));

    if (!examenId) {
      this.chargement = false;
      this.erreur = 'Aucun examen sélectionné pour le viewer DICOM.';
      return;
    }

    this.api.getDicomSource(examenId).subscribe({
      next: async (source) => {
        this.sourceDicom = source;
        await this.afficherImageDicom(source.orthancFileUrl);
      },
      error: async () => {
        // Fallback vers une image DICOM de démonstration si aucune instance n'est trouvée.
        const demoUrl =
          'https://raw.githubusercontent.com/cornerstonejs/cornerstone3D/main/packages/dicomImageLoader/testImages/CT000000.dcm';

        await this.afficherImageDicom(demoUrl);
      }
    });
  }

  ngOnDestroy(): void {
    const element = this.dicomViewportRef?.nativeElement;
    if (element) {
      cornerstone.disable(element);
    }
  }

  private async afficherImageDicom(imageUrl: string): Promise<void> {
    try {
      await this.initialiserCornerstone();

      const imageId = `wadouri:${imageUrl}`;
      const image = await cornerstone.loadAndCacheImage(imageId);
      cornerstone.displayImage(this.dicomViewportRef.nativeElement, image);
      this.erreur = '';
    } catch {
      this.erreur = 'Impossible de charger l\'image DICOM dans Cornerstone.';
    } finally {
      this.chargement = false;
    }
  }

  private async initialiserCornerstone(): Promise<void> {
    if (this.cornerstoneReady) {
      return;
    }

    (cornerstoneWADOImageLoader as any).external.cornerstone = cornerstone;
    (cornerstoneWADOImageLoader as any).external.dicomParser = dicomParser;
    cornerstone.enable(this.dicomViewportRef.nativeElement);

    this.cornerstoneReady = true;
  }
}
