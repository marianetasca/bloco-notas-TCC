import React from 'react';
import Notes from '../components/Notes';

export default function Dashboard() {
  console.log('Dashboard renderizado');

  return (
    <div>
      <h1>Dashboard</h1>
      <Notes />
    </div>
  );
}
