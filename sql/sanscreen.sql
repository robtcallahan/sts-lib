SELECT    sum(v.capacityGB) /1024 AS provisionedGb
FROM      path p,
          volume v,
          array a
WHERE     a.name = 'sym-000192600777'
  AND     p.arrayId = a.id
  AND     v.id = p.volumeId;


select v.name, v.capacityGB, v.virtual, h.name
from   array a,
    path p,
    volume v,
    host h
where  a.name = 'sym-000192600777'
  and  p.arrayId = a.id
  and  v.id = p.volumeId
  and  h.id = p.hostId
order by v.capacityGB, h.name
limit 0, 800;

select a.name, h.name, v.name, v.label, v.capacityGB
  from array a,
      path p,
      volume v,
      host h
  where h.name LIKE 'stihcpresx0%'
  and p.hostId = h.id
  and a.id = p.arrayId
  and v.id = p.volumeId
  order by v.capacityGB, h.name
  limit 0, 500;

select a.name, v.name, h.name, vm.initiatorPortOrNodeWwn, vm.protocolController, vm.storagePortWwn
  from array a,
      path p,
      volume v,
      host h,
      volume_mask vm
  where h.name = 'stnpcdvesx01.va.neustar.com'
  and p.hostId = h.id
  and a.id = p.arrayId
  and v.id = p.volumeId
  and vm.volumeId = v.id;

select v.name, vm.*
  from volume v,
      volume_mask vm
  where vm.volumeId = v.id
  and vm.arrayId = 1285949;

select distinct a.name
  from array a,
      volume_mask vm
  where a.id = vm.arrayId
