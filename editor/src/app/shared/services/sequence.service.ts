import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, Observable } from 'rxjs';
import { environment } from '@environments/environment';
import { SequenceType } from '@/shared/models/sequence-type';
import { ApiResponse } from '@/shared/interfaces/api-response';

interface SequenceSimulationResult {
  number: string;
}

@Injectable({
  providedIn: 'root',
})
export class SequenceService {
  protected httpClient: HttpClient = inject(HttpClient);

  simulateSequence(type: SequenceType): Observable<string> {
    const baseEndpoint = environment.api.endpoint;
    const endpoint = `${baseEndpoint}/sequences/${type}/simulate`;

    return this.httpClient
      .get<ApiResponse<SequenceSimulationResult>>(endpoint)
      .pipe(map((resp) => resp.data.number));
  }
}
