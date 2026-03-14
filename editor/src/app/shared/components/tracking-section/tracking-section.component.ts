import { ChangeDetectionStrategy, Component, input } from '@angular/core';
import { DatePipe, NgClass } from '@angular/common';
// Models
import { Entity } from '@/shared/models/entity';

@Component({
  selector: 'app-tracking-section',
  changeDetection: ChangeDetectionStrategy.OnPush,
  imports: [DatePipe, NgClass],
  templateUrl: './tracking-section.component.html',
  styleUrl: './tracking-section.component.less',
})
export class TrackingSectionComponent {
  entity = input.required<Entity>();
  color = input<string>('primary');
}
